<?php

namespace App\Http\Controllers\Backend;

use App\Models\AdminOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AdminOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backend.admin_offer.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
      
        $offers = AdminOffer::query();
        $url = url('/backend/admin-offers/');
        return Datatables::of($offers)
            ->addColumn('action', function ($offer) use($url) {
                return '
                <a href="'.$url.'/'.$offer->id.'" class="btn btn-primary m-btn m-btn--icon">
                <span><i class="flaticon-edit"></i><span>'.trans('lang.edit').'</span></span></a>
                <a href="" data-id="'.$offer->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            ->editColumn('image', function ($offer){
                return '
                <a href='.asset("uploads/$offer->image").' data-lity><img style="border-radius:50%" src='.asset("uploads/$offer->image").' width="100" height="100"></a>';
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }


     /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminOffer $offer
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin_offer.create');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|max:5000',
            'description' => 'required|string',
            'from'        => 'required|after_or_equal:'.date('Y-m-d'),
            'to'          => 'required|after_or_equal:start_at',
        ]);

        $inputs = $request->only('description', 'from', 'to');

        $inputs['image'] = $request->image->store('admin_offers');

        AdminOffer::create($inputs);

        return response('success');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminOffer $offer
     * @return \Illuminate\Http\Response
     */
    public function show(AdminOffer $offer)
    {
        return view('backend.admin_offer.update', compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminOffer $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminOffer $offer)
    {
        $request->validate([
            'image'       => 'image|max:5000',
            'description' => 'required|string',
            'from'        => 'required|after_or_equal:'.date('Y-m-d'),
            'to'          => 'required|after_or_equal:start_at',
        ]);

        $inputs = $request->only('description', 'from', 'to');

        if($request->image != null){
            $inputs['image'] = $request->image->store('admin_offers');

            $this->removeFileIfExists($offer->image);
        }

        $offer->update($inputs);

        return response('success');
    }

    /**
     * destroy
     */
    public function destroy(AdminOffer $offer)
    {
        if($offer->image != null){
            $this->removeFileIfExists($offer->image);
        }

        $offer->delete();
    }


     /**
     * remove file if exists
     * 
     * @param $name
     * return boolean
     */
    protected function removeFileIfExists($name)
    {
        if($name != null){
            $path = public_path("uploads/$name");
            if(file_exists($path)){
                unlink($path);
            }
        }

        return true;
    }

}
