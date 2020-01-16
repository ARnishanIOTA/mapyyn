<?php

namespace App\Http\Controllers\Backend;

use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.faq.index');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
      
        $faqs = Faq::query();
        $url = url('/backend/faq/');
        return Datatables::of($faqs)
            ->addColumn('action', function ($faq) use($url) {
                return '
                <a href="'.$url.'/'.$faq->id.'" class="btn btn-primary m-btn m-btn--icon">
                <span><i class="flaticon-edit"></i><span>'.trans('lang.edit').'</span></span></a>
                <a href="" data-id="'.$faq->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            ->editColumn('title_en', function ($faq) use($url) {
                $title = 'title_'.LaravelLocalization::getCurrentLocale();
                return $faq->$title;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string',
            'title_en' => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
        ]);

        $inputs = $request->only(['title_ar', 'title_en', 'description_ar', 'description_en']);

        Faq::create($inputs);

        return response('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        return view('backend.faq.update', compact('faq'));
    }

    

  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'title_ar' => 'required|string',
            'title_en' => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
        ]);

        $inputs = $request->only(['title_ar', 'title_en', 'description_ar', 'description_en']);
        
        $faq->update($inputs);

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
    }
}
