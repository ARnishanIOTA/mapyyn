<?php

namespace App\Http\Controllers\Auth\Provider;

use App\Models\Country;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\ProviderCategory;
use App\Models\MobileNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:providers');
    }


    

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('providers');
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|numeric|unique:providers,phone',
            'country' => 'required|string|max:255',
            'categories' => 'required|array',
            'email' => 'required|string|email|max:255|unique:providers',
            'password' => 'required|string|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/',
            'logo' => 'required|image|max:5000'
        ]);

        $inputs = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
        ]);

        if($request->other_city != null){
            $inputs['city'] = $request->other_city;
        }else{
            $inputs['city'] = $request->city;
        }

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        $country = Country::where('id', $request->country)->first();
        $inputs['country']  = $country->$name;
        
        $inputs['password'] = bcrypt($request->password);
        $inputs['logo']    = $request->logo->store('providers');

        $provider = Provider::create($inputs);

        foreach(request('categories') as $category){
            ProviderCategory::create([
                'category_id' => $category,
                'provider_id' => $provider->id
            ]);
        }


        $notify['message_ar'] = 'تسجيل جديد بواسطة : ' . $provider->first_name . ' '  . $provider->first_name;
        $notify['message_en'] = 'new registration from : ' . $provider->last_name . ' '  . $provider->last_name;
        $notify['user_type']  = 7;
        $notify['type']  = 'new_register';
        $notify['provider_id']  = $provider->id;

        MobileNotification::create($notify);
        
        $provider->sendActivateNotification();

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $message = 'تم التسجيل بنجاح ... في انتظار موافقة المدير';
        }else{
            $message = 'successfullt ... waiting approval from admin';  
        }
        return back()->with('success', $message);
    }
}
