<?php

namespace App\Http\Controllers\Front;

use App\Models\Chat;
use App\Models\Offer;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = Chat::with(['messages' => function($query){
            $query->select('id', 'message', 'chat_id', 'created_at')->orderBy('id', 'desc');
        }, 'provider'])->where('client_id', auth('clients')->id())->orderBy('id', 'desc')->paginate(5)->toArray();

        return view('front.chat', compact('chats'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        $request->validate([
            'message'          => 'required|string',
            'type'             => 'required|integer|'.Rule::in([1,2]),
            'offer_type'       => 'required|integer|'.Rule::in([1,2]),
            'offer_id'         => 'required_if:offer_type,1|integer|exists:offers,id',
            'request_offer_id' => 'required_if:offer_type,2|integer|exists:request_offers,id',
        ]);

        $chat_inputs = [
            'client_id'   => auth('clients')->id(),
            'offer_type'  => $request->offer_type,
            'type'        => $request->type
        ];

        if($request->type == 2){
            if($request->offer_type == 1){
                $offer = Offer::find($request->offer_id);
            }else{
                $offer = RequestOfferProvider::where('provider_id', $request->provider_id)->first();
                if($offer == null){
                    return back();
                }
            }
            $chat_inputs['provider_id'] = $offer->provider_id;
            $notify['provider_id']      = $offer->provider_id;
        }

        if(request('offer_type') == 1){
            $check = Chat::where('client_id', auth('clients')->id())->where('offer_id', $request->offer_id);
            if($request->type == 1){
                $check =  $check->where('provider_id', null)->first();
            }else{
                $check =  $check->where('provider_id', $offer->provider_id)->first();
            }
            $chat_inputs['offer_id'] = $request->offer_id;
        }else{
            $check = Chat::where('client_id', auth('clients')->id())->where('request_offer_id', $request->request_offer_id);
            if($request->type == 1){
                $check =  $check->where('provider_id', null)->first();
            }else{
                $check =  $check->where('provider_id', $offer->provider_id)->first();
            }
            $chat_inputs['request_offer_id'] = $request->request_offer_id;
        }

        if($check == null){
            $check = Chat::create($chat_inputs);
        }
        
        $inputs = $request->only('message');
        $inputs['chat_id'] = $check->id;
        $inputs['type'] = 1;

        ChatMessage::create($inputs);

        $number         =  $request->offer_type == 1 ? $request->offer_id : $request->request_offer_id;
        if($request->type == 1){
            $data['message_en']  = "You Got a new Message about request number : " .  $number;
            $data['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $number;
            $data['client_id']   =  $check->client_id;
            $data['chat_id']     =  $check->id;
            $data['user_type']   = 1;
            $data['type']        = 'chat';
            MobileNotification::create($data);
        }else{
            $data['message_en']  = "You Got a new Message about request number : " .  $number;
            $data['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $number;
            $data['client_id']   =  $check->client_id;
            $data['chat_id']     =  $check->id;
            $data['provider_id'] =  $check->provider_id;
            $data['user_type']   = 16;
            $data['type']        = 'chat';
            MobileNotification::create($data);
            $data['number'] = $number;
            $this->sendFcm($data);
        }

        return back()->with('success', 'success');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if($chat->client_id != auth('clients')->id()){
            return back(); 
        }
        
        $inputs = $request->only('message');
        $inputs['chat_id'] = $chat->id;
        $inputs['type'] = 1;

        ChatMessage::create($inputs);
        $number         =  $chat->offer_type == 1 ? $chat->offer_id : $chat->request_offer_id;
        if($chat->type == 1){
            $data['message_en']  = "You Got a new Message about request number : " .  $number;
            $data['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $number;
            $data['client_id']   =  $chat->client_id;
            $data['chat_id']     =  $chat->id;
            $data['user_type']   = 1;
            $data['type']        = 'chat';
            MobileNotification::create($data);
        }else{
            $data['message_en']  = "You Got a new Message about request number : " .  $number;
            $data['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $number;
            $data['client_id']   =  $chat->client_id;
            $data['chat_id']     =  $chat->id;
            $data['provider_id'] =  $chat->provider_id;
            $data['user_type']   = 16;
            $data['type']        = 'chat';
            MobileNotification::create($data);
            $data['number'] = $number;
            $this->sendFcm($data);
        }
        

        return back()->with('success', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {

        if($chat->client_id != auth('clients')->id()){
            return back();
        }

        $chat = $chat->load(['messages' => function($query){
            $query->orderBy('id', 'asc');
        }]);


        return view('front.chat-details',compact('chat'));
    }


    public function sendFcm(array $data)
    {
        if($data['user_type'] == 1){
            $message  = "You Got a new Message from mapyyn" ;
        }else{
            $message   = "You Got a new Message about request number : " .  $data['number'] ;
        }

        $dataId  = $data['chat_id'];

        if($data['user_type'] == 16){
            $token = \App\Models\Provider::where('id', $data['client_id'])->pluck('fcm_token')->toArray();
        }else{
            $token = [];
        }
        

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
