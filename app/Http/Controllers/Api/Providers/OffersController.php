<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Provider\OffersCollection;

class OffersController extends ApiController
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
            $description = 'description_ar';
        }else{
            $name = 'name_en';
            $description = 'description_en';  
        }
        $offers = Offer::with(['country' => function($query)  use($name){
            $query->select('id', "$name as name");
        },'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }])->select('id', 'lat', 'category_id', 'provider_id', 'country_id','city_id', 'currency','lng', 'price', 'location', 'from', 'to', 'end_at')
        ->where('provider_id', $this->provider()->id())
        ->orderBy('id', 'desc')
        ->where('status', 1)
        // ->where('end_at', '>=', date('Y-m-d'))
        ->paginate(5);

        return $this->apiResponse(new OffersCollection($offers));
    }

   

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        if(request('lang') == 'ar'){
            $name = 'name_ar';
            $description = 'description_ar';
        }else{
            $name = 'name_en';
            $description = 'description_en';  
        }
        $offer = Offer::with(['country' => function($query)  use($name){
            $query->select('id', "$name as name");
        },'city' => function($query) use($name){
            $query->select('id', "$name as name");
        },'provider' => function($query) use($name){
            $query->select('id', 'first_name', 'last_name', 'rate', 'city');
        },'images'])
        ->select('id', "$description as description", 'event_name', 'category_id','provider_id','country_id','city_id','hotel_level', 'persons', 'transport','days','price', 'location', 'currency' ,'from', 'to', 'end_at')
        ->where('id', $offer->id)
        ->where('provider_id', $this->provider()->id())
        ->where('status', 1)
        ->first();

        return $this->apiResponse($offer);
    }


    /**
     * Display the specified resource.
     *
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function request_offer(RequestOffer $requestOffer)
    {
        // if($requestOffer->client_id != $this->client()->id()){
        //     return $this->apiResponse((object)[], 'invalid request', 404); 
        // }

        // $check = Payment::where('request_offer_id', $requestOffer->id)->first();
        // if($check != null){
        //     $paymentFile = PaymentAttachment::where('payment_id', $check->id)->first();
        // }else{
        //     $paymentFile = (object)['test'];
        // }

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

        // $data = ['request_offers' => $requestOffer , 'files' => $paymentFile];
        $requestOffer = RequestOffer::where('id', $requestOffer->id)->first(["$from_city as from_city", "$to_city as to_city", "$from_country as from_country", "$to_country as to_country", 'request_offers.*']);
        return $this->apiResponse($requestOffer);
    }

}
