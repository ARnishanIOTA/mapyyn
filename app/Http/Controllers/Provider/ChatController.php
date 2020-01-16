<?php

namespace App\Http\Controllers\Provider;

use App\Models\Chat;
use App\Models\Client;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chat = Chat::with('messages')->where('provider_id', auth('providers')->id())->where('type', 3)->orderBy('id', 'asc')->first();

        return view('providers.chat.details', compact('chat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $chat = Chat::where('provider_id', auth('providers')->id())->where('type', 3)->first();
        if($chat == null){
            $chat = Chat::create([
                'provider_id' => auth('providers')->id(),
                'type' => 3,
                'offer_type' => 0
            ]);
        }
        ChatMessage::create([
            'message' => $request->message,
            'type'    => 2,
            'chat_id' => $chat->id
        ]);

        $inputs['provider_id'] = $chat->provider_id;
        $inputs['user_type']   = 2;
        $inputs['type']        = 'admin_chat';
        $inputs['message_en']  = 'you have new message from :' . $chat->provider->first_name . ' ' . $chat->provider->first_name;
        $inputs['message_ar']  = 'لديك رسالة جديدة من :' . $chat->provider->last_name . ' ' . $chat->provider->last_name;
        
        MobileNotification::create($inputs);

        return response('success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clients()
    {
        $chat = Chat::where('provider_id', auth('providers')->id())->where('type','!=', 3)->orderBy('id', 'desc')->first();

        return view('providers.chat.index', compact('chat'));
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $chat = Chat::with(['client' => function($query){
                    $query->select('id', 'first_name', 'last_name');
                }])->where('provider_id', auth('providers')->id())
                ->where('type','!=', 3);
        $url = url('/providers/chat/clients');
        return Datatables::of($chat)
            ->addColumn('action', function ($cha) use($url) {
                return '
                <a href="'. route('providers_chat_clients_details', $cha->id) .'" class="btn btn-success m-btn m-btn--icon">
                <span>'.trans('lang.details').' </span></span></a>';
            })
            ->editColumn('first_name', function ($cha) use($url) {
                return $cha->client->first_name. ' ' .$cha->client->last_name;
            })
            ->editColumn('offer_type', function ($cha) use($url) {
                if($cha->offer_type == 1){
                    return trans('lang.offer');
                }else{
                    return trans('lang.request_offer');
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function client_details(Chat $chat)
    {
        if($chat->provider_id != auth('providers')->id()){
            return back();
        }
        $chat = $chat->load(['messages' => function($query){
            $query->orderBy('id', 'asc');
        }], 'client');
        return view('providers.chat.client_details', compact('chat'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function client_store(Request $request, Chat $chat)
    {
        if($chat->provider_id != auth('providers')->id()){
            return back();
        }

        $request->validate(['message' => 'required|string']);

        $inputs = $request->only('message');
        $inputs['chat_id'] = $chat->id;
        $inputs['type'] = 2;

        ChatMessage::create($inputs);

        $number         =  $chat->offer_type == 1 ? $chat->offer_id : $chat->request_offer_id;
        
        $data['message_en']  = "You Got a new Message about request number : " .  $number;
        $data['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $number;
        $data['client_id']   =  $chat->client_id;
        $data['chat_id']     =  $chat->id;
        $data['provider_id'] =  $chat->provider_id;
        $data['user_type']   = 15;
        $data['type']        = 'chat';
        MobileNotification::create($data); 
        $data['number'] = $number;
        $this->sendFcm($data);

        return response('success');
    }


    public function sendFcm(array $data)
    {
       
        $message   = "You Got a new Message about request number : " .  $data['number'] ;

        $dataId  = $data['chat_id'];

        $token = \App\Models\Client::where('id', $data['client_id'])->pluck('fcm_token')->toArray();
        

        $fcmData = [
            'type'      => 'chat',
            'id'        => $dataId,
            'user_type' => $data['user_type'],
        ];

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'registration_ids' => $token,

            'notification' => [
                "body"     => $message,
                'title'    => 'Mappyen',
                'vibrate'  => 1,
                'sound'    => 1,
                "icon"     => "myicon",
                "color"    => "#2bc0d1"
            ],

            "data" => $fcmData
                
        ];

        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=AAAADGyngdY:APA91bF5jVOhtMfRZD1c45I0wODjB4KQf7t8nsusua_C3bZjudn96NI5i7CXQu9rFOKhfWaYYuc5Qs_Qb_C34d6O65LOvNAnSeTgmJQSJXVy_qeCJJAvVm3Yv7zCeZ4nzYUvNFg8YV5o',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        //  echo $result;dd($result,"ddddddddddd");
        curl_close($ch);
    }

}
