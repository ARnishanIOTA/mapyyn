<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Offer;
use App\Models\Passenger;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\PassengerCollection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PassengerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function offer(Offer $offer)
    {
        $passenger = Passenger::where('client_id', $this->client()->id())->where('offer_id', $offer->id)->get();

        return $this->apiResponse(new PassengerCollection($passenger));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\Offer $offer
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function offerSubmit(Request $request, Offer $offer)
    {
        // $passenger = Passenger::where('client_id', $this->client()->id())->where('offer_id', $offer->id)->count();
        // if($passenger >= $offer->persons){
            // return $this->apiResponse((object)[], 'already complate', 404); 
        // }

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'birthdate' => 'required|date',
            'passport_country' => 'required|string',
            'passport_number' => 'required|string|min:4|max:255',
            'nationality' => 'required|string',
        ]);

        // Passenger::create([
        //     'name'             => $request->name,
        //     'birthdate'        => $request->birthdate,
        //     'passport_country' => $request->passport_country,
        //     'passport_number'  => $request->passport_number,
        //     'nationality'      => $request->nationality,
        //     'offer_id'         => $offer->id,
        //     'client_id'        => $this->client()->id()
        // ]);

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function requestOffer(RequestOffer $requestOffer)
    {
        $passenger = Passenger::where('client_id', $this->client()->id())->where('request_offer_id', $requestOffer->id)->get();
        
        return $this->apiResponse(new PassengerCollection($passenger));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\RequestOffer $requestOffer
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function requestOfferSubmit(Request $request, RequestOffer $requestOffer)
    {
        // if($requestOffer->provider_id == null){
        //      return $this->apiResponse((object)[], 'not complate', 422);
        // }

        if($requestOffer->client_id != $this->client()->id()){
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'غير موثوق', 403);
            }else{
                return $this->apiResponse((object)[], 'unauthroized', 403);
            }
        }

        // $passenger = Passenger::where('client_id', $this->client()->id())->where('request_offer_id', $requestOffer->id)->count();
        
        // $persons = $requestOffer->adult + $requestOffer->children + $requestOffer->babies;

        // if($passenger >= $persons){
        //     return $this->apiResponse((object)[], 'already complate', 404);
        // }

        $request->validate([
            'name'             => 'required|string|min:3|max:255',
            'birthdate'        => 'required|date',
            'passport_country' => 'required|string',
            'passport_number'  => 'required|string|min:4|max:255',
            'nationality'      => 'required|string',
        ]);

        // Passenger::create([
        //     'name'             => $request->name,
        //     'birthdate'        => $request->birthdate,
        //     'passport_country' => $request->passport_country,
        //     'passport_number'  => $request->passport_number,
        //     'nationality'      => $request->nationality,
        //     'request_offer_id' => $requestOffer->id,
        //     'client_id'        => $this->client()->id()
        // ]);

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }

    

}
