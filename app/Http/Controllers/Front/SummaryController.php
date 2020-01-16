<?php

namespace App\Http\Controllers\Front;

use App\Models\Offer;
use App\Models\Payment;
use App\Models\RequestOffer;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function summeryOffer(Offer $offer)
    {
        session_start();

        $checkPayment = Payment::where('offer_id', $offer->id)->where('client_id', auth('clients')->id())->first();
        if($checkPayment != null){
           return back();
        }
        
        $passengers = 0;
        if(isset($_SESSION['offers'])){
            foreach($_SESSION['offers'] as $passenger){
                    if($passenger['offer_id'] == $offer->id){
                        $passengers += 1; 
                    }
            }
        }

        if($passengers < $offer->persons){
            return redirect(route('passengerOffer', ['id' => $offer->id]));
        }

        $passengers = $_SESSION['offers'];
        return view('front.summary',compact('passengers', 'offer'));


    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function summeryRequestOffer(RequestOffer $requestOffer)
    {
        session_start();
        // if($requestOffer->provider_id == null){
        //     return back();
        // }

    $checkPayment = Payment::where('request_offer_id', $requestOffer->id)->where('client_id', auth('clients')->id())->first();
    if($checkPayment != null){
       return back();
    }

    if($requestOffer->client_id != auth('clients')->id()){
        return back();
    }

    $persons = $requestOffer->adult + $requestOffer->children + $requestOffer->babies;

    $passengers = 0;
    if(isset($_SESSION['request_offers'])){
        foreach($_SESSION['request_offers'] as $passenger){
            if($passenger['request_offer_id'] == $requestOffer->id){
                $passengers += 1; 
            }
        }
    }

    if($passengers < $persons){
        return redirect(route('passengerRequestOffer', ['id' => $requestOffer->id]));
    } 

    $passengers = $_SESSION['request_offers'];
    return view('front.summary_request_offer',compact('passengers', 'requestOffer'));
    }

}
