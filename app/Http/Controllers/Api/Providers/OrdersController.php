<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Payment;
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

        $orders = Payment::with(['client' => function($query) {
            $query->select('id', 'first_name', 'last_name', 'phone', 'email');
        } , 'offer' => function($query) {
            $query->select('id','price', 'category_id');
        } , 'requestOffer' => function($query) use($name) {
            $query->select('id', 'category_id');
        }])
        ->where('provider_id', $this->provider()->id())
        ->orderBy('id', 'desc')
        ->paginate(5);

        return $this->apiResponse(new PaymentsCollection($orders));
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
            $name = 'name_ar';
            $description = 'description_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        }else{
            $description = 'description_en';
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        }

        $order = Payment::with(['client' => function($query) {
            $query->select('id', 'first_name', 'last_name', 'phone', 'email');
        } , 'offer', 'requestOffer' => function($query) use($from_city , $to_city, $from_country, $to_country) {
            $query->select("$from_city as from_city", "$to_city as to_city", "$from_country as from_country", "$to_country as to_country", 'request_offers.*');
        }])
        ->where('provider_id', $this->provider()->id())
        ->where('id', $payment->id)
        ->orderBy('id', 'desc')
        ->paginate(5);

        return $this->apiResponse($order);
    }
}
