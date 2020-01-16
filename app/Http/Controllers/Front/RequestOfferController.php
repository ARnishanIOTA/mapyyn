<?php

namespace App\Http\Controllers\Front;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Interest;
use App\Models\Provider;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Models\ProviderCategory;
use App\Models\PaymentAttachment;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RequestOfferRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RequestOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();

        $client = new Client();
        $request = $client->get('https://www.thesportsdb.com/api/v1/json/1/all_leagues.php');
        $data = json_decode($request->getBody()->getContents());
        
        $leagues = [];
        foreach($data->leagues as $key => $league){
            if($league->strSport == "Soccer"){
                $leagues[] = ['id' => $league->idLeague, 'name' => $league->strLeague];
            }
        }
        
        return view('front.request_offer', compact('countries', 'leagues'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function events($id)
    {
        $client = new Client();
        $request = $client->get("https://www.thesportsdb.com/api/v1/json/1/eventsnextleague.php?id=$id");
        $data = json_decode($request->getBody()->getContents());
        
        $events = [];
        if(count((array)$data->events) > 0){
            foreach($data->events as $key => $event){
                if($event->strSport == "Soccer"){
                    $events[] = ['id' => $event->idEvent, 'name' => $event->strEvent, 'date' => $event->dateEvent];
                }
            }
        }else{
            if(LaravelLocalization::getCurrentLocale() == 'ar'){
                $message = 'لا يوجد نتائج';
                $try = 'حاول في وقت لاحق';
            }else{
                $message = 'there aree no result';
                $try = 'try later';
            }
            $events = 0;
        }
        return response($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestOfferRequest $request)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $message = 'يجب ان تكون عدد ايام الرد اقل من تاريخ الرحلة';
        }else{
            $message = 'days must be less than trip go date';
        }
        $date = new Carbon(date('Y-m-d'));
        $check = $date->addDays(request('reply_time'));
        if($check >= request('go_date')){
            return response(['danger' => $message], 422);
        }

        $inputs = $request->only([
            'go_date',
            'back_date',
            'trip_stop',
            'transport',
            'hotel_level',
            'category_id',
            'adult',
            'children',
            'babies',
            'price',
            'help',
            'change_date',
            'note'
        ]);

        $country = Country::find($request->from_country);
        $inputs['from_country_ar'] = $country->name_ar;
        $inputs['from_country_en'] = $country->name_en;
        
        $country = Country::find($request->to_country);
        $inputs['to_country_ar'] = $country->name_ar;
        $inputs['to_country_en'] = $country->name_en;

        /*============================================== */
            $client = new Client();
            $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->from_city&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=en");
            $data = json_decode($link->getBody()->getContents());

            if($data->status == 'INVALID_REQUEST'){
                return response('invalid_address');
            }
            $inputs['from_city_en'] = $data->result->address_components[0]->long_name;
            $client = new Client();
            $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->from_city&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=ar");
            $data = json_decode($link->getBody()->getContents());
            $inputs['from_city_ar'] = $data->result->address_components[0]->long_name;
        /**============================================== */
        /**========================================= */
            $client = new Client();
            $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->to_city&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=en");
            $data = json_decode($link->getBody()->getContents());
            if($data->status == 'INVALID_REQUEST'){
                return response('invalid_address');
            }
            $inputs['to_city_en'] = $data->result->address_components[0]->long_name;

            $client = new Client();
            $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->to_city&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=ar");
            $data = json_decode($link->getBody()->getContents());
            $inputs['to_city_ar'] = $data->result->address_components[0]->long_name;
        /**=========================================== */


        $days = abs(strtotime($request->back_date) - strtotime($request->go_date)) / 3600;
        $inputs['days'] = $days / 24;

        $inputs['client_id'] = auth('clients')->id();

        if($request->category_id == 3){
            $client = new Client();
            $link = $client->get("https://www.thesportsdb.com/api/v1/json/1/lookupevent.php?id=$request->event");
            $data = json_decode($link->getBody()->getContents());

            $object = $data->events[0];
            $inputs['event_name']     = $object->strEvent;
            $inputs['league']      = $object->strLeague;
        }

        $providerIds = ProviderCategory::where('category_id', request('category_id'))->pluck('provider_id');
        $providerFinal = Provider::whereIn('id', $providerIds)->get();
        $date = new Carbon(date('Y-m-d'));
        $inputs['reply_time'] = $date->addDays(request('reply_time'));

        $requestOffer = RequestOffer::create($inputs);

        if(request('interests') != null){
            foreach(request('interests') as $interest){
                Interest::create(['request_offer_id' => $requestOffer->id, 'title' => $interest]);
            }
        }
        

        foreach($providerFinal->unique('id') as $provider){
            RequestOfferProvider::create([
                'provider_id' => $provider->id,
                'request_offer_id' => $requestOffer->id
            ]);

            $notify2['client_id'] = auth('clients')->id();
            $notify2['message_en'] = "Recieving Offers Requests number : " . $requestOffer->id ;
            $notify2['message_ar'] = "يوجد عرض جديد للرحلة رقم : " . $requestOffer->id;
            $notify2['user_type']        = 6;
            $notify2['type']             = 'new_request_offer';
            $notify2['provider_id']      = $provider->id;
            $notify2['request_offer_id'] = $requestOffer->id;
            MobileNotification::create($notify2);
        }

        
        $tokens2 = Provider::whereIn('id', $providerIds)->where('fcm_token', '!=', null)->pluck('fcm_token')->toArray();
        $data2['request_offer_id'] = $requestOffer->id;
        $data2['tokens'] = $tokens2;
        $this->sendFcm2( $data2);

        auth('clients')->user()->sendNewRequestOfferNotification();

        return redirect('/my-offers');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
     public function accept(Request $request, RequestOffer $requestOffer)
    {
        $request->validate([
            'provider_id' => 'required|integer|exists:providers,id'
        ]);

        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

    // if($requestOffer->provider_id != null){
    //     return back(); 
    // }

    // $requestOfferMobel = $requestOffer;
    
    $reply = RequestOfferProvider::where('request_offer_id',$requestOffer->id)
    ->where('provider_id',$request->provider_id)->first();
    // $reply->status = 'accepted';
    // $reply->save();
    Session::put('reply_id', $reply->provider_id);


    $check = Payment::where('client_id', auth('clients')->id())->where('request_offer_id', $requestOffer->id)->first();
    if($check != null){
        $payment = PaymentAttachment::where('payment_id', $check->id)->get();
    }else{
        $payment = null;
    }
   

    $providerOffer = RequestOfferProvider::with('provider')->where('provider_id', $reply->provider_id)->where('request_offer_id', $requestOffer->id)->first();

    return view('front.buy-request-offer', compact('requestOffer', 'providerOffer', 'payment'));
}


    //  /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  RequestOffer $requestOffer
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Request $request, RequestOffer $requestOffer)
    // {
        
    //     if($requestOffer->client_id != auth('clients')->id()){
    //         return back();
    //     }
        
    //     $offer = $requestOffer->load('provider');

    //     $providerOffer = RequestOfferProvider::where('provider_id', $requestOffer->provider_id)->where('request_offer_id', $requestOffer->id)->first();

    //     return view('front.buy-request-offer', compact('requestOffer', 'providerOffer'));
    // }


    /**
     * Display the specified resource.
     *
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function buy(RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

        // $offer = $requestOffer->load('provider', 'interests');

        $providerOffer = RequestOfferProvider::where('provider_id', $requestOffer->provider_id)->where('request_offer_id', $requestOffer->id)->first();

        return view('front.buy-request-offer', compact('requestOffer', 'providerOffer'));
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != auth('clients')->id()){
            return back(); 
        }

        if($requestOffer->provider_id != null){
            return back(); 
        }
        
        $request->validate([
            'provider_id' => 'required|integer|exists:providers,id',
            'reason' => 'required|string'
        ]);


        RequestOfferProvider::where('request_offer_id', $requestOffer->id)
        ->where('provider_id', $request->provider_id)
        ->update(['status' => 'reject', 'reason' => $request->reason]);
        
        $inputs['user_type'] = 6;
        $inputs['type'] = 'request_offer';
        $inputs['status'] = 'reject';
        $inputs['request_offer_id'] = $requestOffer->id;
        $inputs['provider_id'] = $request->provider_id;
        $this->sendNotification($inputs);

        return back()->with('success', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    private function sendFcm(array $data)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fcmData = [
            'type'      => $data['type'],
            'id'        => $data['request_offer_id'],
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


    private function sendFcm2(array $data)
    {
        $dataId      = $data['request_offer_id'];
        $message     = "Recieving Offers Requests number : " . $data['request_offer_id'] ;

        $fcmData = [
            'type'      => 'new_request_offer',
            'id'        => $dataId,
            'user_type' => 6,
        ];
        

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'registration_ids' => $data['tokens'],

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
