<?php

namespace App\Http\Controllers\Front;

use App\Models\Client;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProfileController extends Controller
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
        
        return view('front.my-profile', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function update(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|min:3|max:255', 
            'last_name' => 'required|string|min:3|max:255', 
            'email' => 'required|string|email|min:3|max:255|unique:clients,email,'.auth('clients')->id(), 
            'phone' => 'required|numeric|unique:clients,phone,'.auth('clients')->id(),
            'code' => 'required|integer'
        ];
            
        $inputs = $request->only('first_name', 'last_name', 'email', 'phone', 'code');
        
        $user = Client::find(auth('clients')->id());

        if($request->password != null){
            if (Hash::check($request->password, $user->password)) {
                if(LaravelLocalization::getCurrentLocale() == 'ar'){
                    return response(['password' => 'يجب اختيار كلمة مرور مختلفة عن كلمة المرور الحالية'], 404);
                }else{
                    return response(['password' => 'You must enter password not same with current password'], 404);
                }
            }
            $rules['password'] = 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
            $inputs['password'] = bcrypt($request->password);
        }

        $request->validate($rules);

        $user->update($inputs);

        if($request->password != null){
            $user->sendChangePasswordNotification();
            auth('clients')->logout();
        }
        return response('success');
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
