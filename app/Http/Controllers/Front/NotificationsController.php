<?php

namespace App\Http\Controllers\Front;

use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
            $title = 'title_ar';
            $message = 'message_ar';
            $from_city = 'from_city_ar';
            $to_city   = 'to_city_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        }else{
            $name         = 'name_en';
            $title        = 'title_en';
            $message      = 'message_en'; 
            $from_city    = 'from_city_en';
            $to_city      = 'to_city_en'; 
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        }

        MobileNotification::where('client_id', auth('clients')->id())->whereIn('user_type', ['3','5','9','14','15'])->update(['read_at' => date('Y-m-d')]);

        $notifications = MobileNotification::with(['offer' => function($query) use($name){
            $query->select('id','city_id', 'country_id', 'category_id', 'price');
            }, 'offer.country' => function($query) use($name){
                $query->select('id', "$name as name");
            },'offer.city' => function($query) use($name){
                $query->select('id', "$name as name");
            }, 'requestOffer' => function($query) use($to_city, $to_country) {
                $query->select('id', 'category_id', "$to_country as country", "$to_city as city");
            }])
            ->whereIn('user_type', [3,5,9,14,15])
            ->where('client_id', auth('clients')->id())
            ->select('id', "$title as title", "$message as message", 'client_id', 'offer_id', 'request_offer_id', 'chat_id', 'type', 'chat_id')
            ->orderBy('id', 'desc')
            ->paginate(10);

            return view('front.notifications', compact('notifications'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        MobileNotification::where('client_id', auth('clients')->id())->whereIn('user_type', ['3','5','9','14','15'])->delete();

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(MobileNotification $mobileNotification)
    {
        if($mobileNotification->client_id != auth('clients')->id()){
          return back();
        }

        $mobileNotification->delete();

        return back();

    }
}
