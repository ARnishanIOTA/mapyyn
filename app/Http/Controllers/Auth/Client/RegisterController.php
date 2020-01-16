<?php

namespace App\Http\Controllers\Auth\Client;


use Mail;
use App\Mail\ActivationEmail;
use App\Models\Client;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

    use RegistersUsers;

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
        $this->middleware('guest:clients');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $countries = Country::all();
        return view('auth.client.register', compact('countries'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'code'  => 'required|integer',
            'phone' => 'required|numeric|unique:clients,phone',
            'country' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Client
     */
    protected function create(array $data)
    {
        return Client::create([
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'code'            => $data['code'],
            'country'         => $data['country'],
            'city'            => $data['city'],
            'activation_code' => $this->generate(),
            'password'        => Hash::make($data['password']),
        ]);
         

    }


    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('clients');
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        event(new Registered($user));
        // $phone = $user->code.$user->phone;

        // $client = new \GuzzleHttp\Client();
        // $message = 'Mappyn , Activation Code : ' . $user->activation_code;

        // $requestSms = $client->get("http://apps.gateway.sa/vendorsms/pushsms.aspx?user=mapyyn&password=azdf123450&msisdn=$phone&sid=MAPYYN-SMS&msg=$message&fl=1");
        // $data = json_decode($requestSms->getBody()->getContents());
        // $data = (array)$data;
        
        // if($data['ErrorCode'] != '000'){
        //     if(LaravelLocalization::getCurrentLocale() == 'en'){
        //         $message = 'Something Wrong , Please contact with admin';
        //     }else{
        //         $message = 'حدث خطأ, برجاء التواصل مع الادمن';
        //     }
        //     return back()->with('danger', $message);
        //     // return $this->apiResponse(null, $data['ErrorMessage'], 422);
        // }

        // $user->sendActivateNotification();

        // $notify['message_en'] = "New Client Registred : ". $user->first_name . ' ' . $user->first_name ;
        // $notify['message_ar'] = "تسجيل جديد للعميل : " . $user->last_name . ' ' . $user->last_name ;
        // $notify['client_id']  = $user->id;
        // $notify['type'] = 'register_request_client';
        // $notify['user_type'] = 33;
        // MobileNotification::create($notify);

        // if(LaravelLocalization::getCurrentLocale() == 'en'){
        //     $message = 'Well Done , Activation code sent to your Phone , please activate your account';
        // }else{
        //     $message = 'حسنا , تم ارسال كود التفعيل الى الهاتف الخاص بك , من فضلك فم بتفعيل حسابك';
        // }
        

        // return redirect('/activation')->with('success', $message);
        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
    protected function registered(Request $request, $user)
    {
         Mail::to($user->email)->send(new ActivationEmail($user));
         if(LaravelLocalization::getCurrentLocale() == 'en'){
            $message = 'Well Done , Activation code sent to your Phone , please activate your account';
        }else{
            $message = 'حسنا , تم ارسال كود التفعيل الى الهاتف الخاص بك , من فضلك فم بتفعيل حسابك';
        }
        return redirect('/activation')->with('success', $message);
    }
     


    /**
     * generate code
     * 
     * @return string
     */
    private function generate()
    {
        $generate = rand(1000000, 9999999);
        $client = Client::where('activation_code', $generate)->exists();
        if($client){
            return $this->generate();
        }
        return $generate;
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showActivationForm()
    {
        return view('auth.client.activation');
    }


    /**
     * Handle a activation request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activation(Request $request)
    {
        $request->validate([
            'activation_code' => 'required|integer|digits:7', 
            'email' => 'required|string|exists:clients'
        ]);

        $client = Client::where('activation_code', $request->activation_code)->where('email', $request->email)->first();
        if($client != null){
            $client->activation_code = null;
            $client->is_active = 1;
            $client->save();

            $client->sendWelcomeNotification();

            if(LaravelLocalization::getCurrentLocale() == 'en'){
                $message = 'Well Done , Your Account Has Been Activated';
            }else{
                $message = 'حسنا , تم تفعيل حسابك بنجاح';
            }
            return redirect('/login')->with('success', $message);
        }else{
            if(LaravelLocalization::getCurrentLocale() == 'en'){
                $message = 'Email Or Code wrong';
            }else{
                $message = 'البريد الالكتروني او كود التفعيل غير صحيح';
            }
            return back()->with('danger', $message);
        }
        
    }
}
