<?php

namespace App\Http\Controllers\Front;

use App\Models\Country;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ContactUsController extends Controller
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
        return view('front.contact_us', compact('countries'));
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
            'name'    => 'required|string|min:3|max:255',
            'subject' => 'required|string|min:3|max:255',
            'email'   => 'required|string|email',
            'phone'   => 'required|string',
            'country_id'   => 'required|integer|exists:countries,id',
            'message' => 'required|string',
        ]);

        $session = time() - Session::get('counter');
        if($session < 60){
            $count = 60 - $session;
            return response(['invalid' => $count]);
        }

        $country = Country::find(request('country_id'));

        $inputs = $request->only(['name', 'email', 'subject', 'message']);
        $inputs['phone'] = $country->phonecode.request('phone');

        $model = ContactUs::create($inputs);

        $notify['message_en'] = " New Contact Us Message : ". $model->email ;
        $notify['message_ar'] = " رسالة تواصل جديدة  : " . $model->email ;
        $notify['type']       = 'contactus';
        $notify['user_type']  = 35;
        MobileNotification::create($notify);

        $model->sendSubscribeNotification();

        Session::put('counter', time());
        
        return response('success');
    }

}
