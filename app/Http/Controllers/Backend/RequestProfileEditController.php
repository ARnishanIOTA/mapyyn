<?php

namespace App\Http\Controllers\Backend;

use App\Models\Provider;
use App\Models\EditProfile;
use Illuminate\Http\Request;
use App\Models\ProviderCategory;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use App\Models\EditProviderCategory;

class RequestProfileEditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = EditProfile::with('provider.editCategories')->get();

        return view('backend.edit_profile_request.index', compact('profiles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function status(EditProfile $editProfile, $status)
    {
        if($status == 1){
            $provider = Provider::where('id', $editProfile->provider_id)->first();
            $provider->update([
                'first_name' => $editProfile->first_name,
                'last_name' => $editProfile->last_name,
                'email' => $editProfile->email,
                'phone' => $editProfile->phone,
                'code'    => $editProfile->code,
                'country' => $editProfile->country,
                'city' => $editProfile->city,
                'address' => $editProfile->address,
            ]);


            $categories = EditProviderCategory::where('provider_id', $editProfile->provider_id)->get();

            if($categories->count() > 0){
                ProviderCategory::where('provider_id', $editProfile->provider_id)->delete();
                foreach ($categories as $category) {
                    ProviderCategory::create([
                        'provider_id' => $editProfile->provider_id,
                        'category_id' => $category->category_id,
                    ]);
                }
            }
            

            $notify['message_en'] = "your profile has been updated" ;
            $notify['message_ar'] = "تم تعديل الصفحة الشخصية الخاصة بك" ;
            $notify['provider_id'] = $editProfile->provider_id;
            $notify['type'] = 'accept';
            $notify['user_type'] = 12;
            MobileNotification::create($notify);
            
            EditProviderCategory::where('provider_id', $editProfile->provider_id)->delete();
            EditProfile::where('provider_id', $editProfile->provider_id)->delete();
            $notify['token'] = Provider::where('id', $editProfile->provider_id)->pluck('fcm_token');
            $this->sendFcm($notify);
        }else{
            $notify['message_en'] = "your request about update profile has been rejected" ;
            $notify['message_ar'] = "تم رفض طلب تعديل الصفحة الشخصية الخاصة بك" ;
            $notify['provider_id'] = $editProfile->provider_id;
            $notify['type'] = 'reject';
            $notify['user_type'] = 12;
            MobileNotification::create($notify);

            EditProviderCategory::where('provider_id', $editProfile->provider_id)->delete();
            EditProfile::where('provider_id', $editProfile->provider_id)->delete();
            $notify['token'] = Provider::where('id', $editProfile->provider_id)->pluck('fcm_token');
            $this->sendFcm($notify);

            
        }

        return redirect(route('edit_profile_request'));
    }



    private function sendFcm(array $data)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fcmData = [
            'type'      => $data['type'],
            'id'        => $data['provider_id'],
            'user_type' => $data['user_type'],
        ];

        $fields = [
            'registration_ids' => $data['token'],

            'notification' => [
                "body"     => $data['message_en'],
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
