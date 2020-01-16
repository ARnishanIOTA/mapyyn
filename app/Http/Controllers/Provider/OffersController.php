<?php

namespace App\Http\Controllers\Provider;

use App\Models\City;
use App\Models\Offer;
use GuzzleHttp\Client;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\OfferImage;
use Illuminate\Http\Request;
use App\Models\ProviderCategory;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Providers\OfferRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('providers.offers.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $offers = Offer::with('country', 'city')->where('provider_id', auth('providers')->id());

        $url = url('/providers/offers');
        return Datatables::of($offers)
            ->addColumn('action', function ($offer) use($url) {
                return '
                <a href="'.$url . '/' .$offer->id.'" class="btn btn-success m-btn m-btn--icon">
                <span>'.trans('lang.details').' </span></span></a>
                <a href="" data-id="'.$offer->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            
            ->editColumn('category_id', function ($offer) {
                if($offer->category_id == 1){
                    return trans('lang.entertainment');
                }elseif($offer->category_id == 2){
                    return trans('lang.educational');
                }elseif($offer->category_id == 3){
                    return trans('lang.sport');
                }else{
                    return trans('lang.medical');
                }
            })
            ->editColumn('country', function ($offer) {
                if(LaravelLocalization::getCurrentLocale() == 'ar'){
                    return $offer->country->name_ar;
                }else{
                    return $offer->country->name_en;
                }
            })
            ->editColumn('city_id', function ($offer) {
                if(LaravelLocalization::getCurrentLocale() == 'ar'){
                    $name = 'name_ar';
                }else{
                    $name = 'name_en';
                }
                return $offer->city->$name;
            })

            ->editColumn('currency', function ($offer) {
                return trans("lang.$offer->currency");
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProviderCategory::where('provider_id', auth('providers')->id())->pluck('category_id')->toArray();

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $countries = Country::select('id',"$name as name", 'sortname', 'latitude', 'longitude')->get();

        if(in_array(3, $categories)){
            $client = new Client();
            $request = $client->get('https://www.thesportsdb.com/api/v1/json/1/all_leagues.php');
            $data = json_decode($request->getBody()->getContents());
            
            $leagues = [];
            foreach($data->leagues as $key => $league){
                if($league->strSport == "Soccer"){
                    $leagues[] = ['id' => $league->idLeague, 'name' => $league->strLeague];
                }
            }
        }else{
            $leagues = [];
        }

        
        return view('providers.offers.create', compact('leagues', 'countries', 'categories'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function events($id)
    {
        $client = new Client();
        $request = $client->get("https://www.thesportsdb.com/api/v1/json/1/eventsnextleague.php?id=$id");
        $data = json_decode($request->getBody()->getContents());
        
        $events = [];
        foreach($data->events as $key => $event){
            if($event->strSport == "Soccer"){
                $events[] = ['id' => $event->idEvent, 'name' => $event->strEvent, 'date' => $event->dateEvent];
            }
        }
        return response($events);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        $inputs = $request->only([
            'category_id',
            'country_id',
            'description_ar',
            'description_en',
            'hotel_level',
            'location',
            'end_at',
            'from',
            'to',
            'days',
            'persons',
            'transport',
            'price',
            'currency',
            'lat',
            'lng',
        ]);

        $city = City::where('place_id', $request->city_id)->first();
        if($city == null){
            $cityInputs = ['country_id' => $request->country_id , 'place_id' => $request->city_id];
            
            $client = new Client();
            $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->city_id&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=en");
            $data = json_decode($link->getBody()->getContents());
            if($data->status == 'INVALID_REQUEST'){
                return response('invalid_address');
            }

            $cityInputs['name_en'] = $data->result->address_components[0]->long_name;

            $client = new Client();
            $link = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$request->city_id&key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&language=ar");
            $data = json_decode($link->getBody()->getContents());
            $cityInputs['name_ar'] = $data->result->address_components[0]->long_name;

            $city = City::create($cityInputs);
            $inputs['city_id'] = $city->id;
        }else{
            $inputs['city_id'] = $city->id;
        }

        $categories = ProviderCategory::where('provider_id', auth('providers')->id())->pluck('category_id')->toArray();

        if($request->category_id == 3){
            if(in_array(3, $categories)){
                $client = new Client();
                $link = $client->get("https://www.thesportsdb.com/api/v1/json/1/lookupevent.php?id=$request->event_id");
                $data = json_decode($link->getBody()->getContents());

                $object = $data->events[0];
                $inputs['event_name']     = $object->strEvent;
                $inputs['league']      = $object->strLeague;
                // $inputs['event_date']  = $object->dateEvent;
            }else{
                return response('invalid category');
            }
        }

        $inputs['provider_id'] = auth('providers')->id();
        DB::transaction(function () use ($inputs, $request) {
            $offer = Offer::create($inputs);
            foreach($request->images as $image){
                OfferImage::create([
                    'offer_id' => $offer->id,
                    'image'    => $image->store('offers')
                ]);
            }
        });

        return response('success');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return view('providers.offers.details', compact('offer'));
    }



    /**
     * Display a listing of the resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cities($id)
    {

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $cities = City::select('id', "$name as name", 'country_id')->where('country_id', $id)->get();
        return response($cities);
    }



    public function destroy($offer)
    {
        $offer = Offer::where('id', $offer)->first();
        if($offer->provider_id != auth('providers')->id()){
            abort(422);
        }

        $clientOffer = Payment::where('offer_id', $offer)->first();

        if($clientOffer == null){
            $offer->delete();
            return response('success');
        }

        abort(422);
    }

}
