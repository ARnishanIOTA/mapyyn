<?php

namespace App\Http\Controllers\Front;

use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;

class SubscribesController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|string|email|unique:subscribes,email']);

        $model = Subscribe::create($request->only('email'));

        $notify['message_en'] = "New Subscriber : ". $model->email ;
        $notify['message_ar'] = "مشترك جديد : " . $model->email ;
        $notify['type']       = 'subscriber';
        $notify['user_type']  = 34;
        MobileNotification::create($notify);

        $model->sendSubscribeNotification();

        return response('success');
    }

    
}
