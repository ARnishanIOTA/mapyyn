<?php

namespace App\Http\Controllers\Front;

use App\Models\Offer;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\Passenger;
use App\Models\ClientOffer;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OrdersController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buyOffer(Request $request, Offer $offer)
    {
        session_start();
        if($_SESSION['offers'] == null){
            return back();
        }
        $checkPayment = Payment::where('offer_id', $offer->id)->where('client_id', auth('clients')->id())->first();
        if($checkPayment != null){
           return back();
        }

        return DB::transaction(function () use($offer){
            $key = Session::get('checkoutId');
            $key = $key->id;

            $url  = "https://oppwa.com/v1/checkouts/$key/payment";
            $url .= "?authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df";
            $url .=	"&authentication.password=xnFQjPpa2z";
            $url .=	"&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea";

            // $url .=	"&merchantTransactionId=$key";
            // $url .=	"&billing.street1=8ac9a4ca692f2ddb01698b90133c34ea";
            // $url .=	"&billing.city=8ac9a4ca692f2ddb01698b90133c34ea";
            // $url .=	"&billing.state=8ac9a4ca692f2ddb01698b90133c34ea";
            // $url .=	"&customer.email=aa@aa.com";
            // $url .=	"&billing.country=EG";
            // $url .=	"&billing.postcode=123456";
            // $url .=	"&customer.givenName=test";
            // $url .=	"&customer.surname=test";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $responseData = json_decode($responseData);

            if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $responseData->result->code) || preg_match("/^(000\.400\.0[^3]|000\.400\.100)/", $responseData->result->code)){

                $payment = Payment::create([
                    'provider_id' => $offer->provider_id,
                    'client_id' => auth('clients')->id(),
                    'transaction_id' => $responseData->id,
                    'offer_id'  => $offer->id,
                    'go_date'   => $_SESSION['checkout']['go_date'],
                    'month'     => date('Y-m'),
                    'price'     => $offer->price
                ]);

                ClientOffer::create([
                    'offer_id' => $offer->id,
                    'client_id' => auth('clients')->id(),
                    'status'    => 'processing'
                ]);

                foreach($_SESSION['offers'] as $passenger){
                    Passenger::create([
                        'name'              => $passenger['name'],
                        'first_name'        => $passenger['first_name'],
                        'last_name'         => $passenger['last_name'],
                        'birthdate'         => $passenger['birthdate'],
                        'passport_country'  => $passenger['passport_country'],
                        'passport_number'   => $passenger['passport_number'],
                        'passport_end_date' => $passenger['passport_end_date'],
                        'nationality'       => $passenger['nationality'],
                        'offer_id'          => $offer->id,
                        'client_id'         => auth('clients')->id(),
                        'payment_id'        => $payment->id
                    ]);
                }


                $notify['user_type']        = 37;
                $notify['offer_id']         = $offer->id;
                $notify['type']             = 'admin_new_payment';
                $notify['message_en']       = "New payment request for offer number : " .  $offer->id ;
                $notify['message_ar']       = "طلب شراء عرض جديد رقم : "  .  $offer->id ;
                
                MobileNotification::create($notify);

                $inputs['user_type'] = 6;
                $inputs['type'] = 'offer';
                $inputs['offer_type'] = 'offer' ;
                $inputs['offer_id'] = $offer->id ;
                $inputs['provider_id'] = $offer->provider_id ;
                $this->sendNotification($inputs);

                if(LaravelLocalization::getCurrentLocale() == 'en'){
                    $message = 'Your Payment Has Been Done ... Waiting Approval From Admin';
                }else{
                    $message = 'عملية الدفع تمت بنجاح ... في انتظار موافقة المدير';
                }
                $client  = Client::find(auth('clients')->id());
                $client->sendByOfferNotification($offer);
                unset($_SESSION['offers']);
                unset($_SESSION['checkout']);
                
                return redirect("/offers/$offer->id")->with('success', $message);
            }else{
                return redirect("/offers/buy/$offer->id")->with('danger', $responseData->result->description);
            }

        });

    }


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buyRequestOffer(Request $request, RequestOffer $requestOffer)
    {
        if($requestOffer->client_id != auth('clients')->id()){
            return back();
        }

        if(Session::get('reply_id') == null){
            return back();
        }
        session_start();

        if($_SESSION['request_offers'] == null){
            return back();
        }
        $checkPayment = Payment::where('request_offer_id', $requestOffer->id)->where('client_id', auth('clients')->id())->first();
        if($checkPayment != null){
           return back();
        }


        return DB::transaction(function () use($requestOffer){

            $key = Session::get('checkoutId');
            $key = $key->id;

            $url  = "https://oppwa.com/v1/checkouts/$key/payment";
            $url .= "?authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df";
            $url .=	"&authentication.password=xnFQjPpa2z";
            $url .=	"&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $responseData = json_decode($responseData);
            if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $responseData->result->code) || preg_match("/^(000\.400\.0[^3]|000\.400\.100)/", $responseData->result->code)){
                $requestOffer->status = 'processing';
                $requestOffer->provider_id = Session::get('reply_id');
                $requestOffer->save();


                $requestOfferProvider = RequestOfferProvider::where('request_offer_id', $requestOffer->id)->where('provider_id', Session::get('reply_id'))->first();
                $requestOffer->provider_price = $requestOfferProvider->price;
                $requestOfferProvider->update(['status' => 'processing']);

                RequestOfferProvider::where('request_offer_id', $requestOffer->id)->where('price', null)->delete();
                RequestOfferProvider::where('request_offer_id', $requestOffer->id)->where('status', 'pending')->update(['status' => 'closed']);
                RequestOfferProvider::where('request_offer_id', $requestOffer->id)->where('status', 'waiting')->update(['status' => 'closed']);

                $requestsOffers = RequestOfferProvider::where('request_offer_id', $requestOffer->id)->where('status', 'closed')->get();
                foreach ($requestsOffers as $requestsOffer) {
                    $inputs['message_en'] = "request offer number : $requestsOffer->request_offer_id has been closed" ;
                    $inputs['message_ar'] = "العرض المقدم رقم : $requestsOffer->request_offer_id  تم اغلاقه" ;
                    $inputs['request_offer_id'] = $requestsOffer->request_offer_id;
                    $inputs['provider_id'] = $requestsOffer->provider_id;
                    $inputs['type'] = 'request_closed';
                    $inputs['user_type'] = 10;
                    MobileNotification::create($inputs);
                }



                $payment = Payment::create([
                    'provider_id' => Session::get('reply_id'),
                    'client_id' => auth('clients')->id(),
                    'transaction_id' => $responseData->id,
                    'request_offer_id' => $requestOffer->id,
                    'month'     => date('Y-m'),
                    'price'     => $requestOfferProvider->price
                ]);

                foreach($_SESSION['request_offers'] as $passenger){
                    Passenger::create([
                        'name'             => $passenger['name'],
                        'first_name'        => $passenger['first_name'],
                        'last_name'         => $passenger['last_name'],
                        'birthdate'         => $passenger['birthdate'],
                        'passport_country'  => $passenger['passport_country'],
                        'passport_number'   => $passenger['passport_number'],
                        'passport_end_date' => $passenger['passport_end_date'],
                        'nationality'      => $passenger['nationality'],
                        'request_offer_id' => $requestOffer->id,
                        'client_id'        => auth('clients')->id(),
                        'payment_id'       => $payment->id
                    ]);
                }

                $notify['user_type']        = 37;
                $notify['request_offer_id'] = $requestOffer->id;
                $notify['type']             = 'admin_new_payment';
                $notify['message_en']       = "New payment request for offer number : " .  $requestOffer->id ;
                $notify['message_ar']       = "طلب شراء عرض جديد رقم : "  .  $requestOffer->id ;
                
                MobileNotification::create($notify);

                $inputs['user_type'] = 6;
                $inputs['type'] = 'offer';
                $inputs['offer_type'] = 'request_offer' ;
                $inputs['request_offer_id'] = $requestOffer->id ;
                $inputs['provider_id']      = $requestOffer->provider_id ;
                $this->sendNotification($inputs);


                $data['request_offer_id'] = $requestOffer->id;
                $this->sendFcm($data);

                if(LaravelLocalization::getCurrentLocale() == 'en'){
                    $message = 'Your Payment Has Been Done ... Waiting Approval From Admin';
                }else{
                    $message = 'عملية الدفع تمت بنجاح ... في انتظار موافقة المدير';
                }
                $client = Client::where('id', auth('clients')->id())->first();
                $client->sendByOfferNotification($requestOffer);
                unset($_SESSION['request_offers']);
                unset($_SESSION['reply_id']);
                unset($_SESSION['checkout']);

                return redirect("/request_offer_provider/accept/$requestOffer->id?provider_id=$requestOffer->provider_id")->with('success', $message);

            }else{
                return redirect("/request_offer_provider/accept/$requestOffer->id?provider_id=$requestOffer->provider_id")->with('danger', $responseData->result->description);
            }
        });
    }



    private function sendFcm(array $data)
    {
        $request_offer_id  = $data['request_offer_id'];

        $providerIds  = RequestOfferProvider::where('request_offer_id', $request_offer_id)->where('status', 'closed')->pluck('provider_id');
        $tokens       = Provider::whereIn('id', $providerIds)->pluck('fcm_token')->toArray();
        $message  = "request offer number : $request_offer_id has been closed" ;

        $fcmData = [
            'type'      => 'request_closed',
            'id'        => $request_offer_id,
            'user_type' => 10,
        ];


        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'registration_ids' => $tokens,

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


    public function request() {
        $url = "https://oppwa.com/v1/checkouts";
        $data = "authentication.userId=8a8294174d0595bb014d05d829e701d1" .
                    "&authentication.password=9TnJPc2n9h" .
                    "&authentication.entityId=8a8294174d0595bb014d05d829cb01cd" .
                    "&amount=92.00" .
                    "&currency=EUR" .
                    "&paymentType=DB";

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

        Session::put('checkoutId', $responseData);
        return back()->with('success', '');
    }


}
