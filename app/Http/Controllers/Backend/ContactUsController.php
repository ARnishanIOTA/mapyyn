<?php

namespace App\Http\Controllers\Backend;

use App\Models\ContactUs;
use App\Mail\ContactUsEmail;
// use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactUs = ContactUs::all();
        return view('backend.contact-us.index', compact('contactUs'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUs $contactUs)
    {
        if($contactUs->is_read != 1){
            $contactUs->is_read = 1;
            $contactUs->save();

            // $noty = Notification::where('notifiable_type', 'contact')->where('notifiable_id', $contactUs->id)->first();
            // if($noty != null){
            //     $noty->read_at = date('Y-m-d');
            //     $noty->save();
            // }
        }
        

        $contact = $contactUs;
        return view('backend.contact-us.reply', compact('contact'));
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactUs $contactUs)
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        retry(5, function()  use ($request, $contactUs) {
            Mail::to($contactUs->email)->send(new ContactUsEmail($request));
        }, 100);

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactUs $contactUs)
    {
        $contactUs->delete();
    }
}
