<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;
use App\Http\Controllers\Api\ApiController;

class SettingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::first();
        if($setting == null){
            return $this->apiResponse((object)[], 'not found', 404);
        }

        return $this->apiResponse(new SettingResource($setting));
    }
}
