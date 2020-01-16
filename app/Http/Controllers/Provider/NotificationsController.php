<?php

namespace App\Http\Controllers\Provider;

use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\DataTables;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        MobileNotification::whereIn('user_type', ['4','6', '8', '10', '12', '16'])->update(['read_at' => date('Y-m-d')]);
        
        return view('providers.notifications');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
            $title = 'title_ar';
            $message = 'message_ar';
            $to_city = 'to_city_ar';
            $to_country = 'to_country_ar';
        }else{
            $name = 'name_en';
            $title = 'title_en';
            $message = 'message_en';  
            $to_city = 'to_city_en';
            $to_country = 'to_country_en';
        }

        $notifications = MobileNotification::with(['offer' => function($query){
            $query->select('id', 'city_id', 'country_id', 'category_id', 'price');
            }, 'offer.country' => function($query) use($name){
                $query->select('id', "$name as name");
            },'offer.city' => function($query) use($name){
                $query->select('id', "$name as name");
            }, 'requestOffer' => function($query) use($to_city, $to_country){
                $query->select('id', 'category_id', "$to_country as country", "$to_city as city");
            }])
            ->whereIn('user_type', ['4','6','8', '10', '12', '16'])
            ->where('provider_id', auth('providers')->id())
            ->select('id', "$title as title", "$message as message", 'client_id', 'offer_id', 'request_offer_id', 'read_at','chat_id', 'type', 'created_at', 'user_type');
            
        return DataTables::of($notifications)
            ->editColumn('message', function ($notification) {
                if($notification->user_type == 4){
                    return '<a href="'.url("providers/chat").'">'.$notification->message.'</a>';
                }elseif($notification->user_type == 6){
                    if($notification->type == 'offer'){
                        return '<a href="'.url("providers/offers/$notification->offer_id").'">'.$notification->message.'</a>';
                    }else{
                        return '<a href="'.url("providers/requests_offers/details/$notification->request_offer_id").'">'.$notification->message.'</a>';
                    }
                }elseif($notification->user_type == 16){
                    return '<a href="'.url("providers/chat/clients/show/$notification->chat_id").'">'.$notification->message.'</a>';
                }else{
                    return $notification->message;
                }
            })
            ->editColumn('read_at', function($notification){
                return $notification->read_at == null ? '---' : $notification->read_at;
            })

            ->addColumn('action', function($notification){
                return '<a href="" data-id="'.$notification->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            ->rawColumns(['message', 'read_at', 'action'])
            ->make(true);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        MobileNotification::where('provider_id', auth('providers')->id())->whereIn('user_type', ['4','6', '8', '10', '12', '16'])->delete();

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(MobileNotification $mobileNotification)
    {
        if($mobileNotification->provider_id != auth('providers')->id()){
            abort(403);
        }

        $mobileNotification->delete();

        return response('success');

    }


    
}
