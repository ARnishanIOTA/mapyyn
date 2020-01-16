<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Offer;
use App\Models\Passenger;
use App\Models\RequestOffer;

class SummaryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function summeryOffer(Offer $offer)
    {
        $passengers = Passenger::where('client_id', auth('clients')->id())->where('offer_id', $offer->id)->get();

        if($passengers->count() < $offer->persons){
            return redirect(route('passengerOffer', ['id' => $offer->id]));
        }

        return view('front.summary',compact('passengers'));


    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function summeryRequestOffer(RequestOffer $requestOffer)
    {
        if($requestOffer->provider_id == null){
            return back();
        }

        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

        $persons = $requestOffer->adult + $requestOffer->children + $requestOffer->babies;

        $passengers = Passenger::where('client_id', auth('clients')->id())->where('request_offer_id', $requestOffer->id)->get();

        if($passengers->count() < $persons){
            return redirect(route('passengerRequestOffer', ['id' => $requestOffer->id]));
        } 

        return view('front.summary',compact('passengers'));
    }
}
