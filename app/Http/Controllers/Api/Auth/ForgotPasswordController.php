<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Client;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\ApiController;

class ForgotPasswordController extends ApiController
{
    /**
     * Forgot Password
     * 
     * @param object $request
     * @return json
     */
    public function forgotPassword(Request $request)
    {
        if($request->is('api/providers/*')){
            $request->validate(['email' => 'required|exists:providers,email']);
            $model = Provider::where('email', $request->email)->first();
            $type = 'providers';
        }else{
            $request->validate(['email' => 'required|exists:clients,email']);
            $model = Client::where('email', $request->email)->first();
            $type = 'client';
        }

        if($model->is_active == 0){
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'يجب تفعيل الحساب اولا', 401);
            }else{
                return $this->apiResponse((object)[], 'you should activate your account first', 401);
            }
        }

        $model->activation_code = $this->generate($type);
        $model->save();

        retry(5, function()  use ($model) {
            Mail::to($model->email)->send(new ForgotPasswordEmail($model));
        }, 100);

        
        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم ارسال الكود الى البريد الخاص بك']);
        }else{
            return $this->apiResponse(['success' => 'check your mail for reset code']);
        }

    }


    /**
     * check reset code
     * 
     * @param object $request
     * @return json
     */
    public function checkCode(Request $request)
    {
        if($request->is('api/providers/*')){
            $request->validate([
                'code' => 'required|exists:providers,activation_code',
                'email' => 'required|exists:providers,email',
            ]);
            $model = Provider::where('email', $request->email)->where('activation_code', $request->code)->first();
        }else{
            $request->validate([
                'code' => 'required|exists:clients,activation_code',
                'email' => 'required|exists:clients,email',
            ]);
            $model = Client::where('email', $request->email)->where('activation_code', $request->code)->first();
        }

        if($model){
            if(request('lang') == 'ar'){
                return $this->apiResponse(['success' => 'كود صحيح']);
            }else{
                return $this->apiResponse(['success' => 'correct code']);
            }
        }else{
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'الكود او البريد خطأ');
            }else{
                return $this->apiResponse((object)[], 'code or email is wrong');
            }
           
        }
    }

    /**
     * new password
     * @param object $request
     * @return json
     */
    public function newPassword (Request $request)
    {
        if($request->is('api/providers/*')){
            $request->validate([
                'code' => 'required|exists:providers,activation_code',
                'email' => 'required|exists:providers,email',
                'password' => 'required|string|min:6',
            ]);
            $model = Provider::where('email', $request->email)->where('activation_code', $request->code)->first();
        }else{
            $request->validate([
                'code' => 'required|exists:clients,activation_code',
                'email' => 'required|exists:clients,email',
                'password' => 'required|string|min:6',
            ]);
            $model = Client::where('email', $request->email)->where('activation_code', $request->code)->first();
                
        }
        
        if($model){
            $model->password = bcrypt($request->password);
            $model->activation_code = null;
            $model->save();
            if(request('lang') == 'ar'){
                return $this->apiResponse(['success' => 'يمكنك تسجيل الدخول الان']);
            }else{
                return $this->apiResponse(['success' => 'well done .. you can login now']);
            }
        }else{
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'الكود او البريد خطأ');
            }else{
                return $this->apiResponse((object)[], 'code or email is wrong');
            }
        }
    }


    /**
     * generate code
     * 
     * @return string
     */
    private function generate($type)
    {
        $generate = rand(1000000, 9999999);
        if($type == 'client'){
            $model = Client::where('activation_code', $generate)->exists();
        }else{
            $model = Provider::where('activation_code', $generate)->exists();
        }
        if($model){
            return $this->generate($type);
        }
        return $generate;
        
    }
}
