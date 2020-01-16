<?php

namespace App\Http\Controllers\Front;

use App\Models\City;
use App\Models\Offer;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Passenger;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function offer(Offer $offer)
    {
        // if($offer->status == 0){
        //     return back();
        // }
        
        // if($offer->end_at < date('Y-m-d')){
        //     return back();
        // }

        session_start();

        $checkPayment = Payment::where('offer_id', $offer->id)->where('client_id', auth('clients')->id())->first();
        if($checkPayment != null){
           return back();
        }


        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        if(!isset($_SESSION['checkout'])){
            $checkoutCheck = true;
            $countries = Country::select('id', "$name as name", 'sortname')->get();
            $cities = City::select('id', "$name as name")->get();
            return view('front.passenger', compact('countries', 'cities', 'checkoutCheck', 'offer'));
        }else{
            $checkoutCheck = false;
        }



        $passengers = 0;

        if(isset($_SESSION['offers'])){
            $sessionCount = count($_SESSION['offers']);
            foreach($_SESSION['offers'] as $passenger){
                if($passenger['offer_id'] == $offer->id){
                    $passengers += 1;
                }
            }
        }else{
            $sessionCount = 0;
        }

        if($passengers >= $offer->persons){
            if($offer->currency == 'dollar'){
                $amount = round($offer->price * 3.75, 2);
            }else{
                $amount = $offer->price;
            }
            $this->requestCheckoutId($amount, $offer->id);
            return redirect("/offers/summary/$offer->id");
        }

        $count = $offer->persons - $passengers;



        $countries = Country::select('id', "$name as name", 'sortname')->get();
        $cities = City::select('id', "$name as name")->get();
        return view('front.passenger', compact('offer', 'count', 'checkoutCheck', 'countries', 'sessionCount', 'cities'));
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
        // if($offer->status == 0){
        //     return back();
        // }
        
        // if($offer->end_at < date('Y-m-d')){
        //     return back();
        // }
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

        if($passengers >= $offer->persons){
            if($offer->currency == 'dollar'){
                $amount = round($offer->price * 3.75, 2);
            }else{
                $amount = $offer->price;
            }

            $this->requestCheckoutId($amount, $offer->id);
            return back();
        }

        $request->validate([
            'name'              => 'required|string|min:3|max:255',
            'first_name'        => 'required|string|min:3|max:255',
            'last_name'         => 'required|string|min:3|max:255',
            'birthdate'         => 'required|date',
            'passport_country'  => 'required|string',
            'passport_number'   => 'required|string|min:4|max:255',
            'passport_end_date' => 'required|date',
            'nationality'       => 'required|string',
        ]);

        $_SESSION['offers'][] = [
            'name'              => $request->name,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'birthdate'         => $request->birthdate,
            'passport_country'  => $request->passport_country,
            'passport_number'   => $request->passport_number,
            'passport_end_date' => $request->passport_end_date,
            'nationality'       => $request->nationality,
            'offer_id'          => $offer->id,
            'client_id'         => auth('clients')->id(),
            'type'              => 'offer'
        ];

        return back();
    }



    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function requestOffer(RequestOffer $requestOffer)
    {
        session_start();
        // dd($_SESSION['passengers']);
        // $_SESSION['passengers'];
        // if($requestOffer->provider_id == null){
        //     return back();
        // }
        // echo"<pre>";
        // print_r($requestOffer);
        // echo "</pre>";

        $checkPayment = Payment::where('request_offer_id', $requestOffer->id)->where('client_id', auth('clients')->id())->first();
        if($checkPayment != null){
           return back();
        }

        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        if(!isset($_SESSION['checkout'])){
            $checkoutCheck = true;
            $countries = Country::select('id', "$name as name", 'sortname')->get();
            $cities = City::select('id', "$name as name")->get();
            return view('front.passenger_request_offer', compact('countries', 'cities', 'checkoutCheck'));
        }else{
            $checkoutCheck = false;
        }

        $passengers = 0;
        if(isset($_SESSION['request_offers'])){
            $sessionCount = count($_SESSION['request_offers']);
            foreach($_SESSION['request_offers'] as $passenger){
                if($passenger['request_offer_id'] == $requestOffer->id){
                    $passengers += 1;
                }
            }

        }else{
            $sessionCount = 0;
        }

        $persons = $requestOffer->adult + $requestOffer->children + $requestOffer->babies;

        if($passengers >= $persons){
            $provider_price = RequestOfferProvider::where('request_offer_id', $requestOffer->id)
            ->where('provider_id', Session::get('reply_id'))->first();

            if($provider_price == null){
                return redirect("/");
            }

            $amount = $provider_price->price;
            // echo "$amount";
            // echo "<br>";
            // echo "$requestOffer->id";
            $this->requestCheckoutId($amount, $requestOffer->id);
            // if(Session::get('checkoutId')){
            //     echo "fgdhjd fgjkfdg j";
            // }

            return redirect("/request_offers/summary/$requestOffer->id");
        }

        $count = $persons - $passengers;

        $countries = Country::select('id', "$name as name")->get();

        return view('front.passenger_request_offer', compact('requestOffer', 'checkoutCheck', 'count', 'countries', 'persons', 'sessionCount'));
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
        session_start();
        $checkPayment = Payment::where('request_offer_id', $requestOffer->id)->where('client_id', auth('clients')->id())->first();
        if($checkPayment != null){
           return back();
        }
        // if($requestOffer->provider_id == null){
        //     return back();
        // }

        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

        $passengers = 0;
        if(isset($_SESSION['request_offers'])){
            foreach($_SESSION['request_offers'] as $passenger){
                if($passenger['request_offer_id'] == $requestOffer->id){
                    $passengers += 1;
                }
            }
        }

        $persons = $requestOffer->adult + $requestOffer->children + $requestOffer->babies;

        if($passengers >= $persons){
            $provider_price = RequestOfferProvider::where('request_offer_id', $requestOffer->id)
            ->where('provider_id', Session::get('reply_id'))->first();

            if($provider_price == null){
                return redirect("/");
            }

            $amount = $provider_price->price;
            $this->requestCheckoutId($amount, $requestOffer->id);
            return redirect("/request_offers/summary/$requestOffer->id");
        }

        $request->validate([
            'name'              => 'required|string|min:3|max:255',
            'first_name'        => 'required|string|min:3|max:255',
            'last_name'         => 'required|string|min:3|max:255',
            'birthdate'         => 'required|date',
            'passport_country'  => 'required|string',
            'passport_number'   => 'required|string|min:4|max:255',
            'passport_end_date' => 'required|date',
            'nationality'       => 'required|string',
        ]);


        $_SESSION['request_offers'][] = [
            'name'              => $request->name,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'birthdate'         => $request->birthdate,
            'passport_country'  => $request->passport_country,
            'passport_number'   => $request->passport_number,
            'passport_end_date' => $request->passport_end_date,
            'nationality'       => $request->nationality,
            'request_offer_id'  => $requestOffer->id,
            'client_id'         => auth('clients')->id(),
            'type'              => 'request_offer'
        ];
        // Passenger::create([
        //     'name'             => $request->name,
        //     'birthdate'        => $request->birthdate,
        //     'passport_country' => $request->passport_country,
        //     'passport_number'  => $request->passport_number,
        //     'nationality'      => $request->nationality,
        //     'request_offer_id' => $requestOffer->id,
        //     'client_id'        => auth('clients')->id()
        // ]);
        return back();
    }




    protected function requestCheckoutId($amount, $id)
    {
        // $url = "https://oppwa.com/v1/checkouts";
        // $data = "authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df" .
        //         "&authentication.password=xnFQjPpa2z" .
        //         "&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea" .
        //         "&amount=$amount" .
        //         "&currency=SAR" .
        //         "&paymentType=DB".
        //         // "&testMode=EXTERNAL".
        //         "&merchantTransactionId=$id".
        //         "&customer.email=".$_SESSION['checkout']['email'].
        //         "&billing.street1=".$_SESSION['checkout']['street1'].
        //         "&billing.city=".$_SESSION['checkout']['city'].
        //         "&billing.state=".$_SESSION['checkout']['state'].
        //         "&billing.country=".$_SESSION['checkout']['country'].
        //         "&billing.postcode=".$_SESSION['checkout']['postcode'].
        //         "&customer.givenName=".$_SESSION['checkout']['givenName'].
        //         "&customer.surname=".$_SESSION['checkout']['surname'];

                $url = "https://oppwa.com/v1/checkouts";
                $data = "authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df" .
                        "&authentication.password=xnFQjPpa2z" .
                        "&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea" .
                        "&amount=$amount" .
                        "&currency=SAR" .
                        "&paymentType=DB".
                        "&merchantTransactionId=$id".
                        "&customer.email=".$_SESSION['checkout']['email'].
                        "&billing.street1=".$_SESSION['checkout']['street1'].
                        "&billing.city=".$_SESSION['checkout']['city'].
                        "&billing.state=".$_SESSION['checkout']['state'].
                        "&billing.country=".$_SESSION['checkout']['country'].
                        "&billing.postcode=".$_SESSION['checkout']['postcode'].
                        "&customer.givenName=".$_SESSION['checkout']['givenName'].
                        "&customer.surname=".$_SESSION['checkout']['surname'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        Session::put('checkoutId', json_decode($responseData));
        return true;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submit_checkout()
    {
        session_start();
        $rules = [
            'email' => 'required|email',
            'street1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postcode' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ];
        if(request('offer_id') != null){
            $offer = Offer::where('id', request('offer_id'))->where('end_at', '>', date('Y-m-d'))->where('status', 1)->first();
            if($offer == null){
                return back();
            }
            $rules['go_date'] = 'required|date|before:'.$offer->to.'|after_or_equal:'.$offer->from;
        }

        request()->validate($rules);

        $_SESSION['checkout'] = [
            'email'     => request('email'),
            'street1'   => request('street1'),
            'city'      => request('city'),
            'state'     => request('state'),
            'country'   => request('country'),
            'postcode'  => request('postcode'),
            'givenName' => request('first_name'),
            'surname'   => request('last_name'),
            'client_id' => auth('clients')->id(),
            'go_date'   => request('go_date'),
        ];

        return back();
    }



}
