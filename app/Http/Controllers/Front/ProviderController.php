<?php

namespace App\Http\Controllers\Front;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use App\Models\ProviderRegisterRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProviderController extends Controller
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
        $countries = Country::select('id', "$name as name", 'phonecode')->get();
        
        return view('front.providers', compact('countries'));
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
            'name' => 'required|string|min:3|max:255',
            'code' => 'required|integer',
            'email' => 'required|email|unique:provider_register_requests,email',
            'phone' => 'required|numeric|unique:provider_register_requests,phone',
            'country_id'   => 'required|integer|exists:countries,id',
            'address' => 'required|string|min:3|max:255'
        ]);


        $inputs = $request->only(['name', 'email', 'address', 'code', 'phone']);

        $inputs['status'] = 0;
        ProviderRegisterRequest::create($inputs);

        $notify['message_en'] = "new register request for : $request->name" ;
        $notify['message_ar'] = "طلب تسجيل مزود خدمة جديد باسم : $request->name" ;
        $notify['user_type'] = 13;
        $notify['type'] = 'register_request';

        MobileNotification::create($notify);

        return back()->with('success', 'successRequest');
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
