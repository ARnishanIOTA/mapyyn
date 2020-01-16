<?php

namespace App\Http\Controllers\Backend;

use App\Models\Client;
use App\Models\Provider;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SendNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.send-notifications.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $notifications = MobileNotification::where('type', 'all');
        $url = url('/backend/send_notifiactions');
        return Datatables::of($notifications)
            // ->addColumn('action', function ($notification) use($url) {
            //     return '
            //     <a href="" data-id="'.$notification->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
            //     <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            // })
            ->editColumn('user_type', function ($notification) use($url) {
                return $notification->user_type == 9 ? trans('lang.client') : trans('lang.provider');
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.send-notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'user_type'    => 'required',
        ]);
        
        $inputs = [
            'title_en' => $request->title, 
            'title_ar' => $request->title,
            'message_en' => $request->message, 
            'message_ar' => $request->message
        ];

        if($request->user_type == 1){
            $inputs['user_type'] = 8;
        }else{
            $inputs['user_type'] = 9;
        }
        $notify['type'] = 'all';
        $notify['message'] = $request->message;

        $inputs['type'] = 'all';
        if($request->user_type == 1){
            $inputs['type'] = 'all';
            $providers = Provider::where('is_active', 1)->get();
            foreach ($providers as $provider) {
                $inputs['provider_id'] = $provider->id;
                
                MobileNotification::create($inputs);
            }
            $notify['user_type'] = 8; 

        }else{
            $clients = Client::where('is_active', 1)->get();
            foreach ($clients as $client) {
                $inputs['client_id'] = $client->id;
                MobileNotification::create($inputs);
            }

            $notify['user_type'] = 9; 
        }

        

        $this->pushNotification($notify);
        
        return response('success');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MobileNotification  $mobileNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobileNotification $mobileNotification)
    {
        // $mobileNotification->delete();
    }
}
