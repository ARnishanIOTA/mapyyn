<?php

namespace App\Http\Controllers\Backend;

use App\Models\MobileNotification;
use Illuminate\Support\Facades\DB;
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
        DB::table('mobile_notifications')->whereIn('user_type', ['1','2','7', '11', '13', '33', '34', '35', '36', '37'])->update(['read_at' => date('Y-m-d')]);

        return view('backend.notifications', compact('notifications'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $title = 'title_ar';
            $message = 'message_ar';
        }else{
            $title = 'title_en';
            $message = 'message_en';  
        }
        $notifications = MobileNotification::whereIn('user_type', [1, 2, 7, 11, 13, 33, 34, 35, 36, 37])
            ->select('id', "$title as title", "$message as message", 'offer_id', 'provider_id','request_offer_id', 'chat_id', 'chat_id', 'type', 'created_at', 'read_at');
        $url = url('/backend/send_notifiactions');
        return DataTables::of($notifications)
            ->editColumn('message', function ($notification){
                if($notification->type == 'offer'){
                    return '<a href='.url("backend/chats/offers/details/$notification->chat_id").'>'.$notification->message.'</a>';
                }elseif($notification->type == 'request_offer'){
                    return '<a href="'.url("backend/chats/request_offers/details/$notification->chat_id").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'new_register'){
                    return '<a href="'.url("backend/providers/$notification->provider_id").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'admin_chat'){
                    return '<a href="'.url("backend/providers/chat/$notification->provider_id").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'profile'){
                    return '<a href="'.url("backend/edit_profile_request").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'register_request'){
                    return '<a href="'.url("backend/providers/register/requests").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'register_request_client'){
                    return '<a href="'.url("backend/clients").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'subscriber'){
                    return '<a href="'.url("backend/subscribes").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'contactus'){
                    return '<a href="'.url("backend/contact_us").'">
                        '.$notification->message.'</a>';
                }elseif($notification->type == 'admin_new_payment'){
                    if($notification->offer_id != null){
                        return '<a href="'.url("backend/providers/offers/details/$notification->offer_id").'">
                        '.$notification->message.'</a>';
                    }else{
                        return '<a href="'.url("backend/request_offers/details/$notification->request_offer_id").'">
                        '.$notification->message.'</a>';
                    }
                }elseif($notification->type == 'admin_payment_status'){
                    if($notification->offer_id != null){
                        return '<a href="'.url("backend/providers/offers/details/$notification->offer_id").'">
                        '.$notification->message.'</a>';
                    }else{
                        return '<a href="'.url("backend/request_offers/details/$notification->request_offer_id").'">
                        '.$notification->message.'</a>';
                    }
                }else{
                    if($notification->offer_id != null){
                        return '<a href="'.url("backend/chats/offers/$notification->chat_id").'">
                        '.$notification->message.'</a>';
                    }else{
                        return '<a href="'.url("backend/chats/request_offers/$notification->chat_id").'">
                        '.$notification->message.'</a>';
                    }
                }
            })
            ->editColumn('read_at', function($notification){
                return $notification->read_at == null ? '---' : $notification->read_at;
            })
            ->addColumn('actions', function ($notification){
                return '
                <a href="" data-id="'.$notification->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            ->rawColumns(['message', 'read_at', 'actions'])
            ->make(true);
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        MobileNotification::whereIn('user_type', [1, 2, 7, 11, 13, 33, 34, 35, 36, 37])->delete();

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(MobileNotification $notification)
    {
        $notification->delete();
    }
    
}
