<?php

namespace App\Http\Controllers\Backend;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $cities = City::with(['country' => function($query) use($name){
            $query->select('id', "$name as name");
        }])->select('id', "$name as name", 'country_id')->get();

        return view('backend.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $countries = Country::select('id', "$name as name")->get();
        return view('backend.cities.create', compact('countries'));
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
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'country_id' => 'required|integer|exists:countries,id',
        ]);

        City::create($request->only(['name_ar', 'name_en', 'country_id']));

        return response('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $countries = Country::select('id', "$name as name")->get();
        return view('backend.cities.update', compact('countries', 'city'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'country_id' => 'required|integer|exists:countries,id',
        ]);

        $city->update($request->only(['name_ar', 'name_en', 'country_id']));

        return response('success');
    }

}
