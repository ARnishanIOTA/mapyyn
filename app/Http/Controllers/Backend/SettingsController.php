<?php

namespace App\Http\Controllers\Backend;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SettingRequest;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::first();
        return view('backend.settings', compact('setting'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(SettingRequest $request)
    {
        $setting = Setting::first();
        
        $inputs = $request->only([
            'fb', 'tw', 'whatsapp', 'email', 'phone', 'about_en', 'about_ar', 'terms_ar','terms_en', 'instagram'
        ]);
        
        if($request->logo != null){
            $inputs['logo'] = $request->logo->store('settings');
        }
        
        $setting->update($inputs);

        return response('success');
    }
  
}
