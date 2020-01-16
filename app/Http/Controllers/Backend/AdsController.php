<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ads::all();

        return view('backend.ads.index', compact('ads'));
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function show(Ads $ads)
    {
        return view('backend.ads.update', compact('ads'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ads $ads)
    {
        $rules = [
            'image_ar' => 'image|max:5000|dimensions:max_width=540,max_height=90',
            'image_en' => 'image|max:5000|dimensions:max_width=540,max_height=90',
            'start_at' => 'required|after_or_equal:'.date('Y-m-d'),
            'end_at'   => 'required|after_or_equal:start_at',
        ];
        
        if($ads->page == 'slider'){
            $rules['image_ar'] = 'image|max:5000|dimensions:max_width=1100,max_height=90';
            $rules['image_en'] = 'image|max:5000|dimensions:max_width=1100,max_height=90';
        }

        $request->validate($rules);

        $inputs = $request->only('start_at', 'end_at');

        if($request->image_ar != null){
            $inputs['image_ar'] = $request->image_ar->store('ads');

            $this->removeFileIfExists($ads->image_ar);
        }

        if($request->image_en != null){
            $inputs['image_en'] = $request->image_en->store('ads');

            $this->removeFileIfExists($ads->image_en);
        }
       

        $ads->update($inputs);

        return response('success');
    }


     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.ads.create');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ads $ads)
    {

        $request->validate([
            'image_ar' => 'required|image|max:5000|dimensions:max_width=1100,max_height=90',
            'image_en' => 'required|image|max:5000|dimensions:max_width=1100,max_height=90',
            'start_at' => 'required|after_or_equal:'.date('Y-m-d'),
            'end_at'   => 'required|after_or_equal:start_at',
        ]);

        $inputs = $request->only('start_at', 'end_at');
        $inputs['page'] = 'slider';

        $inputs['image_ar'] = $request->image_ar->store('ads');
        $inputs['image_en'] = $request->image_en->store('ads');

        Ads::create($inputs);

        return response('success');
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


    /**
     * destroy
     */
    public function destroy(Ads $ads)
    {
        if($ads->page == 'slider'){
            $ads->delete();
            return response('success');
        }

        abort(422);
    }
}
