<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Videos;
use App\Models\City;
use App\Models\Offer;
use App\Models\Country;
use App\Models\Setting;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
            $image = 'image_ar';
        }else{
            $name = 'name_en';
            $image = 'image_en';
        }

        $offers    = Offer::with(['country' => function($query) use($name){
            $query->select('id', "$name as name", 'longitude', 'latitude', 'sortname');
        },'provider' => function($query){
            $query->select('id', 'first_name', 'last_name');
        },'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }])->withCount('rates');

        if(request('country') != null){
            $offers = $offers->where('country_id', request('country'));
        }


        if(request('category') != null){
            $offers = $offers->where('category_id', request('category'));
        }

        if(request('currency') != null){
            $offers = $offers->where('currency', request('currency'));
        }

        if(request('price') != null){
            if(request('price') == 2000){
                $offers = $offers->whereBetween('price', [2000, 4000]);
            }elseif(request('price') == 4000){
                $offers = $offers->whereBetween('price', [4000 , 8000]);
            }elseif(request('price') == 8000){
                $offers = $offers->whereBetween('price', [8000 , 16000]);
            }else{
                $offers = $offers->whereBetween('price', [16000, 50000]);
            }
        }

        if(request('recent') == 'asc'){
            $offers = $offers->orderBy('id', 'asc');
        }else{
            $offers = $offers->orderBy('id', 'desc');
        }

        $offers = $offers->where('end_at', '>=', date('Y-m-d'))->where('status', 1)->get();
        
        $countries = Country::select('id', "$name as name")->get();

        $bestOffers = Offer::with(['images', 'city' => function($query) use($name){
            $query->select('id', "$name as name");
        },'country' => function($query) use($name){
            $query->select('id', "$name as name");
        }])->where('end_at', '>=', date('Y-m-d'))->where('status', 1)->orderBy('rate', 'DESC')->limit(4)->get();

        $sliders = Ads::where('page', 'slider')->where('start_at', '<=', date('Y-m-d'))
        ->where('end_at', '>=', date('Y-m-d'))
        ->select('id', "$image as image")
        ->get();

        $adsBottom = Ads::where('page', 'main_bottom')->where('start_at', '>=', date('Y-m-d'))
        ->where('end_at', '>=', date('Y-m-d'))
        ->select('id', "$image as image")
        ->get();

        

        // $adsBottom = Ads::where('page', 'main_bottom')
        // ->where('start_at', '<=', date('Y-m-d'))
        // ->where('end_at', '>=', date('Y-m-d'))
        // ->select('id', "$image as image")
        // ->orderBy('id', 'desc')
        // ->get()->toArray();


        $setting = Setting::first();
        $Videos = Videos::first();

        return view('welcome',compact('offers','bestOffers','sliders','adsBottom','adsBottom','Videos'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function cities($name)
    {
        if($name == null){
            return response('success');
        }
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $locale = 'name_ar';
        }else{
            $locale = 'name_en';
        }

        $country = Country::where($locale, $name)->first();

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $cities = City::select('id', "$name as name", 'country_id')->where('country_id', $country->id)->get();
        return response($cities);
    }


    public function currency()
    {
        $user = auth('clients')->user() ;

        if($user->currency == 'sar'){
            $user->currency = 'dollar';
            $currency = trans('lang.dollar');
        }else{
            $user->currency = 'sar';
            $currency = trans('lang.sar');
        }

        $user->save();

        return back()->with('status', trans('lang.currency_success'). ' '. $currency);
    }
}
