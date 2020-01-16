<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\OffersCollection;
use App\Http\Resources\Client\MyOffersCollection;

class OffersController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function map(Request $request)
    {
        if(request('lang') == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        if($request->category_id != null){
            $request->validate([
                'category_id' => ['required', Rule::in([0,1,2,3,4])],
            ]);
        }

        if($request->lang == 'ar'){
            $name = 'name_ar';
            $description = 'description_ar';
        }else{
            $name = 'name_en';
            $description = 'description_en';  
        }
        $offers = Offer::with(['provider' => function($query){
            $query->select('id', 'first_name', 'last_name', 'rate');
        },'country' => function($query)  use($name){
            $query->select('id', "$name as name", 'latitude', 'longitude');
        },'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }]);
       

        if ($request->country_id != null) {
            $offers = $offers->where('country_id', $request->country_id);
        }

        if($request->category_id > 0){
            $offers = $offers->where('category_id', request('category_id'));
        }

        if($request->currency != null){
            $offers = $offers->where('currency', request('currency'));
        }

        if($request->price != null){
            if($request->price == 2000){
                $offers = $offers->whereBetween('price', [2000, 4000]);
            }elseif($request->price == 4000){
                $offers = $offers->whereBetween('price', [4000 , 8000]);
            }elseif($request->price == 8000){
                $offers = $offers->whereBetween('price', [8000 , 16000]);
            }else{
                $offers = $offers->whereBetween('price', [16000, 50000]);
            }
        }
        
        $offers = $offers->select('id', 'lat', 'category_id', 'hotel_level', 'event_name','provider_id', 'country_id','city_id', 'lng', 'price', 'location', 'currency','days', 'from', 'to', 'end_at')
        ->where('end_at', '>=', date('Y-m-d'))
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->get();

        return $this->apiResponse(new OffersCollection($offers));
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(request('lang') == 'ar'){
            $name = 'name_ar';
            $description = 'description_ar';
        }else{
            $name = 'name_en';
            $description = 'description_en';  
        }

        $request->validate([
            'category_id' => ['required', Rule::in([0,1,2,3,4])],
            'country_id'  => 'required|integer|exists:countries,id',
        ]);

        if($request->lang == 'ar'){
            $name = 'name_ar';
            $description = 'description_ar';
        }else{
            $name = 'name_en';
            $description = 'description_en';  
        }
        $offers = Offer::with(['provider' => function($query){
            $query->select('id', 'first_name', 'last_name', 'rate');
        },'country' => function($query)  use($name){
            $query->select('id', "$name as name", 'latitude', 'longitude');
        },'city' => function($query) use($name){
            $query->select('id', "$name as name");
        }])->where('country_id', request('country_id'));
        

        if($request->category_id != 0){
            $offers = $offers->where('category_id', request('category_id'));
        }
        $offers = $offers->select('id','lat', 'category_id', 'hotel_level', 'event_name','provider_id', 'currency','country_id','city_id', 'lng', 'price', 'location', 'days', 'from', 'to', 'end_at')
        ->where('end_at', '>=', date('Y-m-d'))
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->paginate(5);

        return $this->apiResponse(new OffersCollection($offers));
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Offer $offer)
    {
        if(request('lang') == 'ar'){
            $name = 'name_ar';
            $description = 'description_ar';
        }else{
            $name = 'name_en';
            $description = 'description_en';  
        }
        $offer = Offer::with(['provider' => function($query){
            $query->select('id', 'first_name', 'last_name', 'rate', 'city');
        },'country' => function($query)  use($name){
            $query->select('id', "$name as name", 'latitude', 'longitude');
        },'city' => function($query)  use($name){
            $query->select('id', "$name as name");
        },'images', 'payment', 'payment.files'])
        ->select('id',"$description as description", 'event_name', 'category_id','provider_id','country_id', 'currency', 'city_id','hotel_level', 'persons', 'transport','days','price', 'location' , 'from', 'to', 'end_at')
        ->where('id', $offer->id)
        // ->where('end_at', '>=', date('Y-m-d'))
        // ->where('status', 1)
        ->first();

        if($offer->payment == null){
            $files = [];
        }else{
            if($this->client()->check()){
                if($offer->payment->client_id == $this->client()->id()){
                    if($offer->payment->files->count() <= 0){
                        $files = [];
                    }else{
                        $files = $offer->payment->files;
                    }
                }else{
                    $files = [];
                }
            }else{
                $files = [];
            }
        }
        $data = [
            'id'          => $offer->id,
            'description' => $offer->description,
            'event_name'  => $offer->event_name,
            'category_id' => $offer->category_id,
            'provider_id' => $offer->provider_id,
            'country_id'  => $offer->country_id,
            'currency'    => $offer->currency,
            'city_id'     => $offer->city_id,
            'hotel_level' => $offer->hotel_level,
            'from'        => $offer->from,
            'to'          => $offer->to, 
            'end_at'      => $offer->end_at,
            'persons'     => $offer->persons,
            'transport'   => $offer->transport,
            'days'        => $offer->days,
            'price'       => $offer->price,
            'location'    => $offer->location,
            'provider'    => $offer->provider,
            'country'     => $offer->country,
            'city'        => $offer->city,
            'images'      => $offer->images,
            'files'       => $files
        ];
        
        return $this->apiResponse($data);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function offers(Request $request)
    {
        if($request->lang == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }

        $offers = Payment::with(['files' , 'offer' => function($query) use($name){
            $query->select('id', 'hotel_level','category_id', 'provider_id', 'country_id','city_id', 'price', 'location', 'currency','created_at');
        },'offer.provider' => function($query){
            $query->select('id', 'first_name', 'last_name', 'rate');
        },'offer.country' => function($query)  use($name){
            $query->select('id', "$name as name");
        },'offer.city' => function($query) use($name){
            $query->select('id', "$name as name");
        }])->where('client_id', $this->client()->id())
        ->where('offer_id', '!=', null)
        ->orderBy('id', 'desc')
        ->paginate(5);

        return $this->apiResponse(new MyOffersCollection($offers));
    }
    
}
