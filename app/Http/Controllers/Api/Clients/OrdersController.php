<?php

namespace App\Http\Controllers\Api\Clients;

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
use App\Models\RequestOfferProvider;
use App\Http\Resources\PaymentsCollection;
use App\Http\Controllers\Api\ApiController;

class OrdersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request('lang') == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        $orders = Payment::with(['provider' => function($query) use($name) {
            $query->select('id', 'first_name', 'last_name');
        } , 'offer' => function($query) use($name) {
            $query->select('id', 'price', 'category_id');
        } , 'requestOffer' => function($query) use($name) {
            $query->select('id', 'category_id');
        }])
        ->where('client_id', $this->client()->id())
        ->orderBy('id', 'desc')->paginate(5);

        return $this->apiResponse(new PaymentsCollection($orders));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Payment $payment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->request_offer_id == null){
            $offer = Offer::where('id', $request->offer_id)->where('provider_id', $request->provider_id)->first();
            if($offer != null){
                $val = $offer->persons;
            }else{
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], 'العرض غير متوفر لهذا المزود', 422);
                }else{
                    return $this->apiResponse((object)[], 'offer not exists for this provider', 422);
                }
            }
        }else{
            $offer = RequestOffer::where('id', $request->request_offer_id)->where('client_id', $this->client()->id())->first();
            if($offer == null){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], 'طلب العرض غير موجود', 422);
                }else{
                    return $this->apiResponse((object)[], 'request offer invalid', 422);
                }

            }else{
                $val = $offer->adult + $offer->children + $offer->babies;
            }
        }
        $rules = [
            'provider_id'                   => 'required|integer|exists:providers,id',
            'transaction_id'                => 'required|string',
            'passengers'                    => "required|array|min:$val|max:$val",
            'passengers.*.name'             => 'required',
            'passengers.*.birthdate'        => 'required',
            'passengers.*.passport_country' => 'required',
            'passengers.*.passport_number'  => 'required',
            'passengers.*.nationality'      => 'required',
        ];

        if($request->request_offer_id == null){
            $check = Payment::where('client_id', $this->client()->id())->where('offer_id', $request->offer_id)->exists();
            if($check){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], 'موجود من قبل', 422);
                }else{
                    return $this->apiResponse((object)[], 'exists before', 422);
                }
            }
            $rules['go_date'] = 'required|date|before:'.$offer->to.'|after_or_equal:'.$offer->from;
            $rules['offer_id'] = 'required|integer|exists:offers,id';
        }else{
            $check = Payment::where('client_id', $this->client()->id())->where('request_offer_id', $request->request_offer_id)->exists();
            if($check){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], 'موجود من قبل', 422);
                }else{
                    return $this->apiResponse((object)[], 'exists before', 422);
                }
            }
            $rules['request_offer_id'] = 'required|integer|exists:request_offers,id';
        }

        $request->validate($rules);


        $inputs = $request->only(['provider_id', 'transaction_id']);

        if($request->request_offer_id == null){
            $type = 'offer';
            $inputs['offer_id'] = $request->offer_id;
            $inputs['go_date'] = $request->go_date;
        }else{
            $type = 'request_offer';
            $inputs['request_offer_id'] = $request->request_offer_id;
            $prices = RequestOfferProvider::where('request_offer_id', $request->request_offer_id)->where('provider_id', $request->provider_id)->first();
            
            $offer->status = 'processing';
            $offer->provider_id = $request->provider_id;
            $offer->provider_price = $prices->price;
            $offer->save();
            $prices->update(['status' => 'processing']);
        }
        $inputs['month'] = date('Y-m');
        $inputs['client_id'] = $this->client()->id();

        DB::transaction(function () use($inputs, $offer, $type, $request){
            if($request->request_offer_id == null){
                $type = 'offer';
                $inputs['offer_id'] = $request->offer_id;
                $notify2['offer_id'] = $request->offer_id;
                $inputs['price'] = $offer->price;
            }else{
                $type = 'request_offer';
                $inputs['request_offer_id'] = $request->request_offer_id;
                $offer->status = 'processing';
                $offer->provider_id = $request->provider_id;
                $offer->save();
                $notify2['request_offer_id'] = $request->request_offer_id;
                $inputs['price'] = $offer->provider_price;
            }



            if($request->request_offer_id != null){
                RequestOfferProvider::where('request_offer_id', $request->request_offer_id)->where('price', null)->delete();
                RequestOfferProvider::where('request_offer_id', $request->request_offer_id)->where('status', 'pending')->update(['status' => 'closed']);
                RequestOfferProvider::where('request_offer_id', $request->request_offer_id)->where('status', 'waiting')->update(['status' => 'closed']);

                $requestsOffers = RequestOfferProvider::where('request_offer_id', $request->request_offer_id)->where('status', 'closed')->get();
                foreach ($requestsOffers as $requestsOffer) {
                    $notify['message_en'] = "request offer number : $requestsOffer->request_offer_id has been closed" ;
                    $notify['message_ar'] = "العرض المقدم رقم : $requestsOffer->request_offer_id  تم اغلاقه" ;
                    $notify['request_offer_id'] = $requestsOffer->request_offer_id;
                    $notify['provider_id'] = $requestsOffer->provider_id;
                    $notify['type'] = 'request_closed';
                    $notify['user_type'] = 10;
                    MobileNotification::create($notify);
                }
            }


            $payment = Payment::create($inputs);
            if($type == 'offer'){
                $name = 'offer_id';
                ClientOffer::create([
                    'offer_id' => $payment->offer_id,
                    'client_id' => $this->client()->id(),
                    'status'    => 'processing'
                ]);
            }else{
                $name = 'request_offer_id';
            }
            foreach(request('passengers') as $passenger){
                Passenger::create([
                    'name'             => $passenger['name'],
                    'first_name'             => $passenger['first_name'],
                    'last_name'             => $passenger['last_name'],
                    'birthdate'        => $passenger['birthdate'],
                    'passport_country' => $passenger['passport_country'],
                    'passport_number'  => $passenger['passport_number'],
                    'passport_end_date'  => $passenger['passport_end_date'],
                    'nationality'      => $passenger['nationality'],
                    $name              => $offer->id,
                    'client_id'        => $this->client()->id(),
                    'payment_id'       => $payment->id
                ]);
            }

            
            $notify2['user_type']        = 37;
            $notify2['type']             = 'admin_new_payment';
            $notify2['message_en']       = "New payment request for offer number : " .  $offer->id ;
            $notify2['message_ar']       = "طلب شراء عرض جديد رقم : "  .  $offer->id ;
            
            MobileNotification::create($notify2);

            $data['request_offer_id'] = $request->request_offer_id;
            $this->sendFcm($data);

        });


        $inputs['user_type'] = 6;
        $inputs['type'] = 'offer';
        if($request->request_offer_id == null){
            $id = $request->offer_id;
            $inputs['offer_type'] = 'offer' ;
        }else{
            $id = $request->request_offer_id;
            $inputs['offer_type'] = 'request_offer' ;
        }
        $this->sendNotification($inputs);
        $client = Client::where('id', $this->client()->id())->first();
        $client->sendByOfferNotification($offer);
        //Please use local files
        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        if(request('lang') == 'ar'){
            $name         = 'name_ar';
            $description  = 'description_ar';
            $from_city    = 'from_city_ar';
            $to_city      = 'to_city_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        }else{
            $name         = 'name_en';
            $description  = 'description_en';
            $from_city    = 'from_city_en';
            $to_city      = 'to_city_en';
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        }



        $order = Payment::with(['provider' => function($query) use($name) {
            $query->select('id', 'first_name', 'last_name');
        } , 'offer', 'requestOffer' => function($query) use($from_city , $to_city, $from_country, $to_country) {
            $query->select("$from_city as from_city", "$to_city as to_city", "$from_country as from_country", "$to_country as to_country", 'request_offers.*');
        }])->where('client_id', $this->client()->id())
        ->where('id', $payment->id)
        ->first();

        return $this->apiResponse($order);
    }



    private function sendFcm(array $data)
    {
        $request_offer_id  = $data['request_offer_id'];

        $providerIds  = RequestOfferProvider::where('request_offer_id', $request_offer_id)->where('status', 'closed')->pluck('provider_id');
        $tokens       = Provider::whereIn('id', $providerIds)->pluck('fcm_token')->toArray();
        $message      = "request offer number : $request_offer_id has been closed" ;

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


    /**
     * payment checkout_id
     */
    public function checkout_id()
    {
        request()->validate([
            'amount' => 'required|numeric',
            'id' => 'required|integer',
            'type' => 'required|boolean',
            'email' => 'required|email',
            'street1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postcode' => 'required|integer',
            'givenName' => 'required|string',
            'surname' => 'required|string',
        ]);



        if(request('type') == 1){
            $payment = Payment::where('offer_id', request('id'))
            ->where('client_id', $this->client()->id())->exists();
        }else{
            $payment = Payment::where('request_offer_id', request('id'))
            ->where('client_id', $this->client()->id())->exists();
        }
        if($payment){
            if(request('lang') == 'ar'){
                return response()->json(['error' => 'هذا العرض تم شراءه من قبل']);
            }else{
                return response()->json(['error' => 'This offer sold before']);
            }
        }

        $price = request('amount');


        // $url = "https://oppwa.com/v1/checkouts";
        // $data = "authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df" .
        //         "&authentication.password=xnFQjPpa2z" .
        //         "&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea" .
        //         "&amount=$price" .
        //         "&currency=SAR" .
        //         "&paymentType=DB".
        //         "&recurringType=REGISTRATION_BASED";
        //       "&notificationUrl=".url('/api/clients/notification-url');


        $url = "https://oppwa.com/v1/checkouts";
        $data = "authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df" .
            "&authentication.password=xnFQjPpa2z" .
            "&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea" .
            "&amount=$price" .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&notificationUrl=".url('/api/clients/notification-url').
            "&merchantTransactionId=".request('id').
            "&customer.email=".request('email').
            "&billing.street1=".request('street1').
            "&billing.city=".request('city').
            "&billing.state=".request('state').
            "&billing.country=".request('country').
            "&billing.postcode=".request('postcode').
            "&customer.givenName=".request('givenName').
            "&customer.surname=".request('surname');


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
            $responseData = json_decode($responseData);

            return response()->json(['checkoutId' => $responseData->id]);

    }


    /**
     * payment notification
     */
    public function notification()
    {
        request()->validate(['resourcePath' => 'required']);

        $path = urldecode(request('resourcePath'));

        $url = "https://oppwa.com$path";
        $url .= "?authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df" .
                "&authentication.password=xnFQjPpa2z" .
                "&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea";

        $client = new \GuzzleHttp\Client;
        $responseData = $client->get($url);
        $data = json_decode($responseData->getBody()->getContents());

        if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $data->result->code) ||
        preg_match("/^(000\.400\.0[^3]|000\.400\.100)/", $data->result->code) ||
        preg_match("/^(000\.200)/", $data->result->code)){
            return response()->json(['paymentResult' => 'OK', 'reason' => "100"]);
        }else{
            return response()->json(['paymentResult' => 'NOK', 'reason' => "100"]);
        }
    }


}
