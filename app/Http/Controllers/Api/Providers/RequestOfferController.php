<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Models\RequestOfferProvider;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Provider\RequestsCollection;

class RequestOfferController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        
        $temp   = RequestOfferProvider::where('provider_id', $this->provider()->id())->where('is_active', 1)->pluck('request_offer_id');
        $offers = RequestOffer::select("$from_city as from_city", "$to_city as to_city", "$from_country as from_country", "$to_country as to_country", 'request_offers.*')
        ->whereIn('id', $temp)->orderBy('id', 'desc')->paginate(5);

        return $this->apiResponse(new RequestsCollection($offers));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RequestOffer $requestOffer)
    {
        $check = RequestOfferProvider::where('provider_id', $this->provider()->id())->where('is_active', 1)->where('request_offer_id', $requestOffer->id)->first();
        if($check == null){
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'طلب غير صالح', 404); 
            }else{
                return $this->apiResponse((object)[], 'invalid request', 404); 
            }
        }
        if($requestOffer->status != 'pending'){
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'طلب غير صالح', 404); 
            }else{
                return $this->apiResponse((object)[], 'invalid request', 404); 
            } 
        }
        $rules = [
            'price'       => 'required|string',
            'description' => 'required|string'
        ];

        if($request->image != null){
            $rules['image'] = 'required|image|max:5000';
        }

        $request->validate($rules);

        $inputs = $request->only('price', 'description');
        if($request->image != null){
            $inputs['image'] = $request->image->store('request-offers');
        }
        $inputs['provider_id'] = $this->provider()->id();
        $inputs['request_offer_id'] = $requestOffer->id;

        $check->update($inputs);

        if(request('lang') == 'ar'){
            $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            $this->apiResponse(['success' => 'Successfully']);
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
        // if($requestOffer->is_active == 0){
        //     return $this->apiResponse((object)[], 'invalid request', 404); 
        // }

        // if($requestOffer->status != 'pending' && $requestOffer->provider_id != $this->provider()->id()){
        //     return $this->apiResponse((object)[], 'invalid request', 404); 
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

        $requestOffer = RequestOffer::where('id', $requestOffer->id)->with(['client' => function($query){
            $query->select('id', 'first_name', 'last_name');
        }, 'interests'])->first(["$from_city as from_city", "$to_city as to_city", "$from_country as from_country", "$to_country as to_country", 'request_offers.*']);
        return $this->apiResponse($requestOffer);
    }
}
