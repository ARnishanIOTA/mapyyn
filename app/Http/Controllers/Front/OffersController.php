<?php

namespace App\Http\Controllers\Front;

use App\Models\Ads;
use App\Models\Rate;
use App\Models\Offer;
use App\Models\Client;
use App\Models\Country;
use App\Models\Payment;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Models\PaymentAttachment;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        $countries = Country::select('id', "$name as name")->get();
        $offers    = Offer::with(['country' => function($query) use($name){
            $query->select('id', "$name as name");
        },  'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }, 'images', 'provider']);

        if($request->country != null){
            $offers = $offers->where('country_id', $request->country);
        }


        if($request->category != null){
            $offers = $offers->where('category_id', $request->category);
        }

        if(request('currency') != null){
            $offers = $offers->where('currency', request('currency'));
        }

        if(request('price') != null){
            if(request('price') == 2000){
                $offers = $offers->whereBetween('price', [2000, 4000]);
            }elseif(request('price') == 4000){
                $offers = $offers->whereBetween('price', [4000 , 8000]);
            }elseif(request('price') == 8000){
                $offers = $offers->whereBetween('price', [8000 , 16000]);
            }else{
                $offers = $offers->whereBetween('price', [16000, 50000]);
            }
        }

        if($request->recent == 'asc'){
            $offers = $offers->orderBy('rate', 'asc');
        }else{
            $offers = $offers->orderBy('rate', 'desc');
        }

        $offers = $offers->where('status', 1)->where('end_at', '>=', date('Y-m-d'))->paginate(10);

        return view('front.offers', compact('offers', 'countries'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
            $image = 'image_ar';
        }else{
            $name = 'name_en';
            $image = 'image_en';
        }

        // if($offer->status == 0){
        //     return back();
        // }

        // if($offer->end_at < date('Y-m-d')){
        //     return back();
        // }

        $offer = Offer::where('id', $offer->id)->with(['provider', 'country' => function($query) use($name) {
            $query->select('id', "$name as name");
        },  'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }, 'images'])->withCount('rates')->first();

        $offerAds = Ads::where('page', 'offer_bottom')->where('start_at', '<=', date('Y-m-d'))
            ->where('end_at', '>=', date('Y-m-d'))
            ->select('id', "$image as image")
            ->get();

        if(auth('clients')->check()){
            $check = Payment::where('client_id', auth('clients')->id())->where('offer_id', $offer->id)->first();
            if($check != null){
                $payment = PaymentAttachment::where('payment_id', $check->id)->get();
            }else{
                $payment = null;
            }
            $checkPayment = Payment::where('client_id', auth('clients')->id())->where('offer_id', $offer->id)->first();
        }else{
            $checkPayment = null;
            $payment = null;
        }

        return view('front.offer-details', compact('offer', 'offerAds', 'checkPayment', 'payment'));
    }


    /**
     * Display the specified resource.
     *
     * @param  Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function buy(Offer $offer)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        if($offer->end_at < date('Y-m-d')){
            return back();
        }

        if($offer->status == 0){
            return back();
        }

        $offer = Offer::where('id', $offer->id)->with(['provider', 'country' => function($query) use($name) {
            $query->select('id', "$name as name");
        }, 'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }, 'images'])->withCount('rates')->first();

        $checkPayment = Payment::where('client_id', auth('clients')->id())->where('offer_id', $offer->id)->first();
        $check = Payment::where('client_id', auth('clients')->id())->where('offer_id', $offer->id)->first();
        if($check != null){
            $payment = PaymentAttachment::where('payment_id', $check->id)->get();
        }else{
            $payment = null;
        }

        return view('front.buy-offer', compact('offer', 'checkPayment', 'payment'));
    }




    public function offers()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        $offers = Client::with(['offers' => function($query){
            $query->orderBy('updated_at','desc');
        },'offers.country' => function($query) use($name){
            $query->select('id', "$name as name");
        },'offers.provider' => function($query){
            $query->select('id','first_name', 'last_name');
        }, 'offers.rates'])->where('id', auth('clients')->id())->first();

        $offers = $offers->offers()->paginate(10);

        $requestOffers = RequestOffer::with(['providerOffer' => function($query){
            $query->where('price', '!=', null)->orderBy('updated_at','desc');
        }, 'providerOffer.provider', 'provider'])->where('client_id', auth('clients')->id())->orderBy('updated_at','desc')->paginate(5, ['*'], 'p');

        return view('front.my-offers', compact('offers', 'requestOffers'));
    }


    /**
     * cancel request offer
     */
    public function cancel_request_offer(RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

        if($requestOffer->provider_id != null){
            return back();
        }

        $requestOffer->delete();

        return back()->with('success', 'success');
    }



    public function rate(Offer $offer)
    {

        $rate = Rate::where('client_id', auth('clients')->id())
            ->where('offer_id', $offer->id)
            ->first();

        if($rate != null){
            $rate->rate = round(request('rate'));
        }else{
            Rate::create([
                'offer_id' => $offer->id,
                'type' => 'offer',
                'client_id' => auth('clients')->id(),
                'rate' => round(request('rate'))
            ]);
        }

        return response('success');
    }
}
