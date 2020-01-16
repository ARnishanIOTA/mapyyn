<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Client;
use App\Models\Provider;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Provider\ProviderResource;

class LoginController extends ApiController
{
    /**
     * Client login
     * 
     * @param object $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientLogin(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email, 
            'password' => $request->password, 
            'is_active' => 1
        ];
    
        if ($token = $this->client()->attempt($credentials)) {
            Client::where('fcm_token', $request->fcm_token)->update(['fcm_token' => null]);
            Provider::where('fcm_token', $request->fcm_token)->update(['fcm_token' => null]);
            $client = Client::where('email', $request->email)->first();
            $client->fcm_token = $request->fcm_token;
            $client->save();

            return $this->apiResponse(new ClientResource($client, $token));
        }

        if(request('lang') == 'ar'){
            return $this->apiResponse((object)[],  'غير موثوق', 401);
        }else{
            return $this->apiResponse((object)[], 'Unauthorized', 401);
        }
        
    }



    /**
     * Provider login
     * 
     * @param object $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function providerLogin(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email, 
            'password' => $request->password, 
            'is_active' => 1
        ];
    
        if ($token = $this->provider()->attempt($credentials)) {

            Provider::where('fcm_token', $request->fcm_token)->update(['fcm_token' => null]);
            Client::where('fcm_token', $request->fcm_token)->update(['fcm_token' => null]);
            
            $provider = Provider::where('email', $request->email)->first();
            $provider->fcm_token = $request->fcm_token;
            $provider->save();

            return $this->apiResponse(new ProviderResource($provider, $token));
        }

        if(request('lang') == 'ar'){
            return $this->apiResponse((object)[], 'غير موثوق', 401);
        }else{
            return $this->apiResponse((object)[], 'Unauthorized', 401);
        }
        
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientRefresh()
    {
        try {
            return $this->apiResponse(['token' => 'Bearer '.$this->client()->refresh()]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return $this->apiResponse((object)[],  'token_blacklisted', 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->apiResponse((object)[],  'token_expired', 401);
        }
        
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function providerRefresh()
    {
        try {
            return $this->apiResponse(['token' => 'Bearer '.$this->provider()->refresh()]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return $this->apiResponse((object)[],  'token_blacklisted', 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->apiResponse((object)[],  'token_expired', 401);
        }
        
    }


    /**
     * logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function providerLogout()
    {
        $user = Provider::find($this->provider()->id());
        $user->fcm_token = null;
        $user->save();
        
        $this->provider()->logout();
        
        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم تسجيل الخروج بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'logout successfully']);
        }
    }

    /**
     * logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientLogout()
    {
        $user = Client::find($this->client()->id());
        $user->fcm_token = null;
        $user->save();
        
        $this->client()->logout();
        
        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم تسجيل الخروج بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'logout successfully']);
        }
    }
}
