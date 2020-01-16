<?php

namespace App\Http\Controllers;

use App\Models\MobileNotification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendNotification(array $data)
    {
        if($data['type'] == 'chat'){
            $input['chat_id'] = $data['chat_id'];
            if($data['user_type'] == 3){
                $input['message_en']   = "You Got a new Message about request number : " .  $data['number'] ;
                $input['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $data['number'] ;
                $input['client_id']   = $data['client_id'];
                $input['user_type']   = 3;
                $input['type'] = $data['type'];
            }else{
                $model = \App\Models\Client::where('id', $data['client_id'])->first();
                $input['message_en']   = "You Got a new Message about request number : " .  $data['number'] ;
                $input['message_ar']  = "تم استلام رسالة جديدة بخصوص الطلب رقم : "  .  $data['number'] ;
                $input['client_id']   = $data['client_id'];
                $input['user_type'] = 1;
                $input['type'] = $data['type'];
            }
        }elseif($data['type'] == 'offer'){
            if($data['offer_type'] == 'offer'){
                $input['offer_id']    = $data['offer_id'];
                $input['message_en']  = "New Request for Buying New trip number " . $data['offer_id'] ;
                $input['message_ar']  = "هناك طلب جديد بشراء احدي رحلاتك رقم : " . $data['offer_id'] ;
                $input['type'] = 'offer';
            }else{
                $data['type'] = 'for_request_offer'; 
                $input['type'] = 'request_offer';
                $input['request_offer_id']    = $data['request_offer_id'];
                $input['message_en']          = "New Request for Buying a request trip number " . $data['request_offer_id'] ;
                $input['message_ar']          = "هناك طلب جديد بشراء طلب رحلة رقم : " . $data['request_offer_id'] ; 
            } 
            $input['provider_id'] = $data['provider_id'];
            $input['user_type']   = 6;
        }elseif($data['type'] == 'request_offer'){
            $input['request_offer_id'] = $data['request_offer_id'];
            $input['message_en'] = "Your Price Rejected number : " . $data['request_offer_id'] ;
            $input['message_ar'] = "السعر المقدم منكم تم رفضه للرحلة رقم : " . $data['request_offer_id'] ;
            $input['request_offer_id'] = $data['request_offer_id'] ;
            $input['provider_id'] = $data['provider_id'];
            $input['user_type'] = 6;
            $input['type'] = $data['type'];
        }elseif($data['type'] == 'new_request_offer'){
            $input['request_offer_id'] = $data['request_offer_id'];
            $input['message_en'] = "Recieving Offers Requests number : " . $data['request_offer_id'] ;
            $input['message_ar'] = "يوجد عرض جديد للرحلة رقم : " . $data['request_offer_id'];
            $input['user_type']  = 6;
            $input['client_id'] = $data['client_id'];
            $input['provider_id'] = $data['provider_id'];
            $input['type'] = $data['type'];
        }else{
            $input['request_offer_id'] = $data['request_offer_id'];
            $input['message_en'] = "You Got New Offers for your trip number : " . $data['request_offer_id'] ;
            $input['message_ar'] = "تم آستلام عروض اسعار جديدة لرحلتك رقم : " . $data['request_offer_id'] ;
            $input['client_id'] = $data['client_id'];
            $input['user_type']  = 5;
            $input['type'] = $data['type'];
        }


        MobileNotification::create($input);

        $this->pushNotification($data);
    }



    public function pushNotification(array $data)
    {
        if($data['type'] == 'chat'){
            if($data['user_type'] == 3){
                $message  = "You Got a new Message from mapyyn" ;
            }else{
                $model = \App\Models\Client::where('id', $data['client_id'])->first();
                $message   = "You Got a new Message about request number : " .  $data['number'] ;
            }

            $dataId      = $data['chat_id'];

        }elseif($data['type'] == 'offer'){
            if($data['offer_type'] == 'offer'){
                $message   = "New Request for Buying New trip number : " . $data['offer_id'] ;
                $dataId      = $data['offer_id'];
            }else{
                $message   = "New Request for Buying Request trip number : " . $data['request_offer_id'] ;
                $dataId      = $data['request_offer_id'];
                $data['type'] = 'request_offer';
            }
        }elseif($data['type'] == 'request_offer'){
            $dataId      = $data['request_offer_id'];
            $message  = "Your Price Rejected for request number : " . $data['request_offer_id'] ;
        }elseif($data['type'] == 'price'){
            $dataId      = $data['request_offer_id'];
            $message     = "You Got New Offers for your trip number : ". $data['request_offer_id'] ;
        }elseif($data['type'] == 'new_request_offer'){
            $dataId      = $data['request_offer_id'];
            $message     = "Recieving Offers Requests number : " . $data['request_offer_id'] ;
        }elseif($data['type'] == 'for_request_offer'){
            $dataId      = $data['request_offer_id'];
            $data['type'] = 'request_offer';
            $message     = "New Request for Buying a request trip number " . $data['request_offer_id'] ; ;
        }else{
            $message = $data['message'];
        }


        if($data['type'] == 'all'){
            if($data['user_type'] == 9){
                $token = \App\Models\Client::where('is_active', 1)->pluck('fcm_token')->toArray();
            }else{
                $token = \App\Models\Provider::where('is_active', 1)->pluck('fcm_token')->toArray();
            }
        }elseif($data['type'] == 'chat'){
            if($data['user_type'] == 3){
                $token = \App\Models\Client::where('id', $data['client_id'])->where('is_active', 1)->pluck('fcm_token')->toArray();
            }else{
                $token = [];
            }
        }elseif($data['type'] == 'offer'){
            $token = \App\Models\Provider::where('id', $data['provider_id'])->where('is_active', 1)->pluck('fcm_token')->toArray();
        }elseif($data['type'] == 'request_offer'){
            $token = \App\Models\Provider::where('id', $data['provider_id'])->where('is_active', 1)->pluck('fcm_token')->toArray();
        }elseif($data['type'] == 'new_request_offer'){
            $token = \App\Models\Provider::where('is_active', 1)->pluck('fcm_token')->toArray();
        }else{
            $token = \App\Models\Client::where('id', $data['client_id'])->where('is_active', 1)->pluck('fcm_token')->toArray();
        }

        if($data['type'] == 'all'){
            $fcmData = [
                'type'      => $data['type'],
                'user_type' => $data['user_type'],
            ];
        }elseif($data['type'] == 'new_request_offer'){
            $fcmData = [
                'type'      => 'new_request_offer',
                'id'        => $dataId,
                'user_type' => $data['user_type'],
            ];
        }else{
            $fcmData = [
                'type'      => $data['type'],
                'id'        => $dataId,
                'user_type' => $data['user_type'],
            ];
        }

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
