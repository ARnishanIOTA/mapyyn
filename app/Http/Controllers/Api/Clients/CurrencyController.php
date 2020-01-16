<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\CurrencyResource;


class CurrencyController extends ApiController
{
    public function currency()
    {
        $user = $this->client()->user() ;

        if($user->currency == 'sar'){
            $user->currency = 'dollar';
        }else{
            $user->currency = 'sar';
        }

        $user->save();

        return $this->apiResponse(new CurrencyResource($this->client()->user()));
    } 
}
