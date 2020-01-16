<?php

namespace App\Http\Controllers\Api\Clients;

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
use App\Models\RequestOfferProvider;
use App\Http\Requests\RequestOfferRequest;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\RequestsCollection;

class RequestOfferController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = RequestOffer::with(['payment', 'payment.files', 'provider', 'providerOffer' => function($query){
            $query->where('price', '!=', null)->orderBy('price', 'desc');
        }, 'providerOffer.provider', 'interests'])
        ->where('client_id', $this->client()->id())
        ->orderBy('id', 'desc')->paginate(5);


        return $this->apiResponse(new RequestsCollection($offers));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestOfferRequest $request)
    {
        $date = new Carbon(date('Y-m-d'));
        $check = $date->addDays(request('reply_time'));
        if($check >= request('go_date')){
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'يجب ان تكون عدد ايام الرد اقل من تاريخ الرحلة', 422);
            }else{
                return $this->apiResponse((object)[], 'reply time must be before go date', 422);
            }
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
            return $this->apiResponse((object)[], 'invalid_address', 422);
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
            return $this->apiResponse((object)[], 'invalid_address', 422);
        }
        $inputs['to_city_en'] = $data->result->address_components[0]->long_name;
        $client = new Client();
        $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->to_city&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=ar");
        $data = json_decode($link->getBody()->getContents());
        $inputs['to_city_ar'] = $data->result->address_components[0]->long_name;
    /**=========================================== */

        $days = abs(strtotime($request->back_date) - strtotime($request->go_date)) / 3600;
        $inputs['days'] = $days / 24;
        $inputs['client_id'] = $this->client()->id();

        if($request->category_id == 3){
            $inputs['league'] = $request->league;
            $inputs['event_name'] = $request->event;
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

            $notify2['client_id'] = $this->client()->id();
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
        $this->client()->user()->sendNewRequestOfferNotification();

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function show(RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != $this->client()->id()){
            return $this->apiResponse((object)[], 'invalid request', 404); 
        }

        $check = Payment::where('request_offer_id', $requestOffer->id)->where('client_id', $this->client()->id())->first();
        if($check != null){
            $paymentFile = PaymentAttachment::where('payment_id', $check->id)->get();
        }else{
            $paymentFile = [];
        }

        $requestOffer = $requestOffer->load('interests');
        if(request('lang') == 'ar'){
            $from_city = 'from_city_ar';
            $to_city   = 'to_city_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        }else{
            $from_city = 'from_city_en';
            $to_city   = 'to_city_en';
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        }

        $requestOffer = RequestOffer::where('id', $requestOffer->id)->with('interests')->first(["$from_city as from_city", "$to_city as to_city", "$from_country as from_country", "$to_country as to_country", 'request_offers.*']);

        $data = ['request_offers' => $requestOffer , 'files' => $paymentFile];
       
        return $this->apiResponse($data);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    // public function accept(Request $request, RequestOffer $requestOffer)
    // {
    //     if($requestOffer->client_id != $this->client()->id()){
    //         return $this->apiResponse((object)[], 'invalid request', 404); 
    //     }
        
    //     $request->validate([
    //         'provider_id' => 'required|integer|exists:providers,id'
    //     ]);

    //     $reply = RequestOfferProvider::where('request_offer_id',$requestOffer->id)
    //     ->where('provider_id',$request->provider_id)->first();
    //     $reply->status = 'accepted';
    //     $reply->save();

    //     $requestOfferId = $requestOffer->id;
    //     $requestOffer->update([
    //         'provider_id' => $request->provider_id,
    //         'status'      => 'accepted',
    //         'price'       => $reply->price
    //     ]);

        

    //     RequestOfferProvider::where('request_offer_id',$requestOfferId)
    //     ->where('status', 'pending')
    //     ->update(['status' => 'closed']);
        
    //     RequestOfferProvider::where('request_offer_id',$requestOfferId)
    //     ->where('status', 'waiting')
    //     ->update(['status' => 'closed']);

    //     $requestOffersClosed = RequestOfferProvider::where('request_offer_id', $requestOfferId)->where('status', 'closed')->get();
    //     foreach ($requestOffersClosed as $requestOffersClose) {
    //         $notify['message_en'] = "request offer number : $requestOffersClose->request_offer_id has been closed" ;
    //         $notify['message_ar'] = "العرض المقدم رقم : $requestOffersClose->request_offer_id  تم اغلاقه" ;
    //         $notify['request_offer_id'] = $requestOffersClose->request_offer_id; 
    //         $notify['provider_id'] = $requestOffersClose->provider_id;
    //         $notify['type'] = 'request_closed';
    //         $notify['user_type'] = 10;
    //         MobileNotification::create($notify);
    //     }

    //     $ids       = RequestOfferProvider::where('request_offer_id', $requestOfferId)->where('status', 'closed')->pluck('provider_id');
    //     $providers = Provider::whereIn('id', $ids)->pluck('fcm_token');
    //     $notify['token'] = $providers;
    //     $this->sendFcm($notify);

        
    //     $inputs['user_type'] = 6;
    //     $inputs['type'] = 'request_offer';
    //     $inputs['request_offer_id'] = $requestOfferId;
    //     $inputs['status'] = 'accepted';
    //     $inputs['provider_id'] = $request->provider_id;
    //     $this->sendNotification($inputs);

    //     return $this->apiResponse(['success' => 'Successfully']);
    // }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != $this->client()->id()){
            return $this->apiResponse((object)[], 'invalid request', 404); 
        }
        
        $request->validate([
            'provider_id' => 'required|integer|exists:providers,id',
            'reason' => 'required|string'
        ]);

        $requestOfferId = $requestOffer->id;

        RequestOfferProvider::where('request_offer_id', $requestOffer->id)
        ->where('provider_id', $request->provider_id)
        ->update(['status' => 'reject', 'reason' => $request->reason]);
        
        $inputs['user_type'] = 6;
        $inputs['type'] = 'request_offer';
        $inputs['status'] = 'reject';
        $inputs['request_offer_id'] = $requestOfferId;
        $inputs['provider_id'] = $request->provider_id;
        $this->sendNotification($inputs);

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }


    /**
     * get all leagues 
     */
    public function leagues()
    {
        $client = new Client();
        $request = $client->get('https://www.thesportsdb.com/api/v1/json/1/all_leagues.php');
        $data = json_decode($request->getBody()->getContents());
        
        $leagues = [];
        foreach($data->leagues as $key => $league){
            if($league->strSport == "Soccer"){
                $leagues[] = ['id' => $league->idLeague, 'name' => $league->strLeague];
            }
        }

        return $this->apiResponse($leagues);
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
        foreach($data->events as $key => $event){
            if($event->strSport == "Soccer"){
                $events[] = ['id' => $event->idEvent, 'name' => $event->strEvent, 'date' => $event->dateEvent];
            }
        }
        return $this->apiResponse($events);
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

    /**
     * cancel request offer
     */
    public function cancel(RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != $this->client()->id()){
            return $this->apiResponse((object)[], 'invalid request', 404); 
        }

        if($requestOffer->provider_id != null){
            return $this->apiResponse((object)[], 'invalid request', 404); 
        }

        $requestOffer->delete();

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }


     /**
     * remove file if exists
     * 
     * @param $name
     * return boolean
     */
    protected function removeFileIfExists($name)
    {
        if($name != null){
            $path = public_path("uploads/$name");
            if(file_exists($path)){
                unlink($path);
            }
        }

        return true;
    }
    
}
