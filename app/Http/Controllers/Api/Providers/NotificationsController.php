<?php

namespace App\Http\Controllers\Api\Providers;

use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Provider\NotificationsCollection;

class NotificationsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->lang == 'ar'){
            $name = 'name_ar';
            $title = 'title_ar';
            $message = 'message_ar';
            $from_city = 'from_city_ar';
            $to_city   = 'to_city_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        }else{
            $name = 'name_en';
            $title = 'title_en';
            $message = 'message_en'; 
            $from_city = 'from_city_en';
            $to_city   = 'to_city_en'; 
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        }

        $notifications = MobileNotification::with(['offer' => function($query) use($name){
                $query->select('id', 'city_id', 'country_id', 'category_id', 'price');
            },'offer.country' => function($query) use($name){
                $query->select('id', "$name as name");
            },'offer.city' => function($query) use($name){
                $query->select('id', "$name as name");
            },'requestOffer' => function($query) use($to_city, $to_country){
                $query->select('id', 'category_id', "$to_country as country", "$to_city as city");
            }, 'requestOffer.providerOffer'])
            ->whereIn('user_type', [4,6,8, 10, 12, 16])
            ->where('provider_id', $this->provider()->id())
            ->select('id', "$title as title", "$message as message", 'provider_id', 'client_id', 'offer_id', 'request_offer_id', 'type', 'chat_id', 'user_type')
            ->orderBy('id', 'desc')
            ->paginate(5);
            
        return $this->apiResponse(new NotificationsCollection($notifications));
    }


    /**
     * count notification
     */
    public function notificationCount()
    {
        $count = MobileNotification::where('provider_id', $this->provider()->id())->whereIn('user_type',  ['4','6', '8', '10', '12', '16'])->where('read_at', null)->count();

        return $this->apiResponse($count);
    }


    /**
     * update notification
     */
    public function notificationUpdate()
    {
        MobileNotification::where('provider_id', $this->provider()->id())->whereIn('user_type',  ['4','6', '8', '10', '12', '16'])->update(['read_at' => date('Y-m-d')]);

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'successfully']);
        }
    }


    

   
}
