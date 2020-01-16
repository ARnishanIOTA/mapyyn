<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ChatCollection;
use App\Http\Controllers\Api\ApiController;

class ChatController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = Chat::with(['messages' => function($query){
            $query->select('id', 'message', 'type', 'chat_id')->orderBy('id', 'desc');
        }, 'client' => function($query){
            $query->select('id', 'first_name', 'last_name');
        }, 'provider' => function($query){
            $query->select('id', 'first_name', 'last_name');
        }])
        ->where('provider_id', $this->provider()->id())
        ->orderBy('id', 'desc')
        ->paginate(6);

        return $this->apiResponse(new ChatCollection($chats));

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

        if($chat->provider_id != $this->provider()->id()){
            return $this->apiResponse((object)[], 'invalid chat', 404); 
        }
        
        $inputs = $request->only('message');
        $inputs['chat_id'] = $chat->id;
        $inputs['type'] = 2;

        ChatMessage::create($inputs);
        $number         =  $chat->offer_type == 1 ? $chat->offer_id : $chat->request_offer_id;
        if($chat->type == 3){
            $data['message_en']  = 'you have new message from :' . $chat->provider->first_name . ' ' . $chat->provider->first_name;
            $data['message_ar']  = 'لديك رسالة جديدة من :' . $chat->provider->last_name . ' ' . $chat->provider->last_name;
            $data['provider_id']   =  $chat->provider_id;
            $data['chat_id']   =  $chat->id;
            $data['user_type']   = 2;
            $data['type']        = 'admin_chat';
            MobileNotification::create($data);
        
        }else{
            $data['message_en']  = "You Got a new Message about request number : " .  $number;
            $data['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $number;
            $data['client_id']   =  $chat->client_id;
            $data['chat_id']   =  $chat->id;
            $data['provider_id'] =  $chat->provider_id;
            $data['user_type']   = 15;
            $data['type']        = 'chat';
            MobileNotification::create($data); 
            $data['number'] = $number;
            $this->sendFcm($data);
        }
        

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }


    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function startChat(Request $request)
    // {
    //     $request->validate([
    //         'message' => 'required|string',
    //         'client_id' => 'required|exists:clients,id'
    //     ]);

    //     $chat = Chat::create([
    //         'provider_id'   => $this->provider()->id(),
    //         'client_id' => $request->client_id,
    //     ]);

    //     $inputs = $request->only('message', 'client_id');
    //     $inputs['provider_id'] = $this->provider()->id();
    //     $inputs['chat_id'] = $chat->id;

    //     ChatMessage::create($inputs);

    //     $this->apiResponse(['success' => 'Successfully']);
    // }

    

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        if($chat->provider_id != $this->provider()->id()){
            return $this->apiResponse((object)[], 'invalid chat', 404); 
        }
        
        $chat = Chat::with(['messages' => function($query){
            $query->orderBy('id', 'asc');
        }, 'client', 'provider'])->where('id', $chat->id)->first();

        return $this->apiResponse(new ChatResource($chat));

    }


    public function sendFcm(array $data)
    {
        if($data['user_type'] == 2){
            $message  = "You Got a new Message from mapyyn" ;
        }else{
            $message   = "You Got a new Message about request number : " .  $data['number'] ;
        }

        $dataId  = $data['chat_id'];

        if($data['user_type'] == 15){
            $token = \App\Models\Client::where('id', $data['client_id'])->pluck('fcm_token')->toArray();
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
