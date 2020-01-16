<?php

namespace App\Http\Controllers\Front;

use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $page
     * @return \Illuminate\Http\Response
     */
    public function index($page)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $about = 'about_ar';
            $terms = 'terms_ar';
            if($page == 'terms'){
                $title = 'شروط الاستخدام';
            }else{
                $title = 'عن الموقع';
            }
        }else{
            $about = 'about_en';
            $terms = 'terms_en';
            if($page == 'terms'){
                $title = 'Terms & Conditions';
            }else{
                $title = 'About Us';
            }
        }

        $setting = Setting::select('id', "$about as about", "$terms as terms")->first();
        $type = '';
        return view('front.pages', compact('setting', 'page', 'title', 'type'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
                $title = 'الاسئلة الشائعة';
        }else{
                $title = 'FAQ';
        }
        $faqs = Faq::all();
        $type = 'faq';
        return view('front.pages', compact('faqs', 'type', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
