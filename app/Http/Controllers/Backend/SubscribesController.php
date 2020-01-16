<?php

namespace App\Http\Controllers\Backend;

use App\Models\Subscribe;
use App\Mail\ContactUsEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SubscribesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribes = Subscribe::all();
        return view('backend.subscribes.index', compact('subscribes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function show(Subscribe  $subscribe)
    {
        return view('backend.subscribes.reply', compact('subscribe'));
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscribe  $subscribe)
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        retry(5, function()  use ($request, $subscribe) {
            Mail::to($subscribe->email)->send(new ContactUsEmail($request));
        }, 100);

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscribe $subscribe)
    {
        $subscribe->delete();
    }
}
