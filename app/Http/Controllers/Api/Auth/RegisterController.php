<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Controllers\Api\ApiController;

class RegisterController extends ApiController
{
    /**
     * Registration
     * 
     * @param object $request
     * @return json
     */
    public function register(RegisterRequest $request)
    {
        $inputs = $request->only('first_name', 'last_name','email','phone', 'code','country','city', 'lang');
        
        $inputs['password'] = bcrypt($request->password);

        $inputs['activation_code'] = $this->generate();

        $model = Client::create($inputs);
        $phone = $model->code.$model->phone;
        $client = new \GuzzleHttp\Client();
        $message = 'Mappyn , Activation Code : ' . $model->activation_code;

        $requestSms = $client->get("http://apps.gateway.sa/vendorsms/pushsms.aspx?user=mapyyn&password=azdf123450&msisdn=$phone&sid=MAPYYN-SMS&msg=$message&fl=1");
        $data = json_decode($requestSms->getBody()->getContents());
        $data = (array)$data;
        
        if($data['ErrorCode'] != '000'){
            return $this->apiResponse((object)[], $data['ErrorMessage'], 422);
        }

        $model->sendActivateNotification();

        $notify['message_en'] = "New Client Registred : ". $model->first_name. ' ' . $model->last_name ;
        $notify['message_ar'] = "تسجيل جديد للعميل : " . $model->first_name. ' ' . $model->last_name ;
        $notify['client_id']  = $model->id;
        $notify['type'] = 'register_request_client';
        $notify['user_type'] = 33;
        MobileNotification::create($notify);

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح .. من فضلك قم بتفعيل الحساب']);
        }else{
            return $this->apiResponse(['success' => 'successfully , please activate your account to login']);
        }
        
    }


    /**
     * account activation
     * 
     * @paran object $request
     * @return json
     */
    public function activate(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:clients,activation_code',
            'email' => 'required|exists:clients,email',
        ]);

        $client = Client::where('activation_code', $request->code)->first();
        $client->activation_code = null;
        $client->is_active = 1;
        $client->save();

        $client->sendWelcomeNotification();

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'يمكنك تسجيل الدخول الان']);
        }else{
            return $this->apiResponse(['success' => 'you can login now']);
        }
    }


    /**
     * resend activation code
     * 
     * @param object $request
     * @return json
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:clients,email',
        ]);

        $model = Client::where('email', $request->email)->first();


        if($model->is_active == 1){
            return $this->apiResponse((object)[], 'you account is already active', 401);
        }

        $client = new \GuzzleHttp\Client();
        $message = 'Mappyn , Activation Code : ' . $model->activation_code;

        $requestSms = $client->get("http://apps.gateway.sa/vendorsms/pushsms.aspx?user=mapyyn&password=azdf123450&msisdn=$model->phone&sid=MAPYYN-SMS&msg=$message&fl=1");
        $data = json_decode($requestSms->getBody()->getContents());
        $data = (array)$data;
        if($data['ErrorCode'] != '000'){
            return $this->apiResponse((object)[], $data['ErrorMessage'], 422);
        }

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم ارسال الكود الى الهاتف الخاص بك']);
        }else{
            return $this->apiResponse(['success' => 'activation code has been send to your phone']);
        }
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
}
