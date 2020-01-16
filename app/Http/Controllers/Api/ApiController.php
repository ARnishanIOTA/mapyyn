<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function __construct()
    {
        if(request('lang') == 'ar'){
            \App::setLocale('ar');
        }else{
            \App::setLocale('en');
        }
    }

    /**
     * return response value
     * 
     * @param mixed $data
     * @param mixed $error
     * @param int $code
     * 
     * @return object
     */
    protected function apiResponse($data = null, $error = null, $code = 200)
    {
        $array = [
            'data'  => $data,
            'error' => ['errors' => $error],
            'code'  => $code
        ];

        return response()->json($array, 200);
    }



    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function client()
    {
        return Auth::guard('apiClient');
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function provider()
    {
        return Auth::guard('apiProvider');
    }
}
