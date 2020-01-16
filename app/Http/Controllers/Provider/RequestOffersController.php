<?php

namespace App\Http\Controllers\Provider;

use Carbon\Carbon;
use App\Models\Interest;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RequestOffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('providers.request-offers.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $offers   = RequestOfferProvider::with('requestOffer', 'requestOffer.client')->where('is_active', 1)->where('provider_id', auth('providers')->id());
        // $offers = RequestOffer::with(['client', 'providerOffer'])->whereIn('id', $temp);
        return Datatables::of($offers)
            ->addColumn('client_id', function ($offer) {
                    return $offer->requestOffer->client->first_name . ' ' . $offer->requestOffer->client->last_name ;
            })
            ->addColumn('category_id', function ($offer) {
                if($offer->requestOffer->category_id == 1){
                    return trans('lang.entertainment');
                }elseif($offer->requestOffer->category_id == 2){
                    return trans('lang.educational');
                }elseif($offer->requestOffer->category_id == 3){
                    return trans('lang.sport');
                }else{
                    return trans('lang.medical');
                }
            })->addColumn('transport', function ($offer) {
                if($offer->requestOffer->transport == 'home'){
                    return trans('lang.home'); 
                }elseif($offer->requestOffer->transport == 'way'){
                    return trans('lang.way');
                }else{
                    return trans('lang.both');
                }      
            })->addColumn('description', function ($offer) {
                return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#m_modal_'.$offer->requestOffer->id.'">'.trans('lang.description').'</button>
                <div class="modal fade show" id="m_modal_'.$offer->requestOffer->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>'.$offer->requestOffer->description.'</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">'.trans('lang.close').'</button>
                            </div>
                        </div>
                    </div>
                </div>';
            })
            ->editColumn('status', function ($offer) {
                return trans("lang.$offer->status");    
            })
            ->addColumn('from_country', function ($offer) {
                $country = 'from_country_'.LaravelLocalization::setLocale();
                return $offer->requestOffer->$country;    
            })
            ->addColumn('to_country', function ($offer) {
                $country = 'to_country_'.LaravelLocalization::setLocale();
                return $offer->requestOffer->$country;    
            })
            ->addColumn('hotel_level', function ($offer) {
                return $offer->requestOffer->hotel_level;    
            })
            ->addColumn('create_at', function ($offer) {
                return $offer->requestOffer->create_at;    
            })
            ->addColumn('action', function ($offer) {
                if($offer->requestOffer->reply_time < date('Y-m-d') && $offer->requestOffer->provider_id != auth('providers')->id()){
                    return '---';
                }elseif($offer->status == 'paid' && $offer->requestOffer->provider_id != auth('providers')->id()){
                    return '---';
                }elseif($offer->status == 'processing' && $offer->requestOffer->provider_id != auth('providers')->id()){
                    return '---';
                }else{
                    return '
                    <a href="'. url('/') . '/providers/requests_offers/details/' .$offer->requestOffer->id.'" class="btn btn-success m-btn m-btn--icon">
                    <span>'.trans('lang.details').' </span></span></a>';
                }
            })
            ->rawColumns(['action', 'description'])
            ->make(true);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(RequestOffer $requestOffer)
    {
        if($requestOffer->is_active == 0){
            return back();
        }
        $requestOffer = $requestOffer->load('client');
        $interests  = Interest::where('request_offer_id', $requestOffer->id)->get(); 
        $status = RequestOfferProvider::where('provider_id', auth('providers')->id())->where('request_offer_id', $requestOffer->id)->first();
        return view('providers.request-offers.details', compact('requestOffer', 'status', 'interests'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function reply(RequestOffer $requestOffer)
    {
        if($requestOffer->is_active == 0){
            return back();
        }

        if($requestOffer->provider_id != null){
            if($requestOffer->provider_id != auth('providers')->id()){
                return back();
            }
        }
        return view('providers.request-offers.reply', compact('requestOffer'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RequestOffer $requestOffer)
    {
        if($requestOffer->is_active == 0){
            return response('closed', 404);
        }

        if($requestOffer->status != 'pending'){
            return response('closed', 404);
        }

        if($requestOffer->reply_time < date('Y-m-d')){
            return response('closed', 404);
        }

        if($requestOffer->provider_id != null){
            return response('closed', 404);
        }

        $check = RequestOfferProvider::where('request_offer_id', $requestOffer->id)
        ->where('provider_id', auth('providers')->id())->first();

        if($check == null){
            return response('closed', 404);
        }

        $rules = [
            'price'       => 'required|string',
            'description' => 'required|string'
        ];

        if($request->image != null){
            $rules['image'] = 'required|image|max:5000';
        }

        $request->validate($rules);

        $inputs = $request->only('price', 'description');
        if($request->image != null){
            $inputs['image'] = $request->image->store('request-offers');
        }
        $inputs['provider_id'] = auth('providers')->id();
        $inputs['request_offer_id'] = $requestOffer->id;
        $inputs['status'] = 'waiting';

        $check->update($inputs);

        $inputs['user_type'] = 5;
        $inputs['type'] = 'price';
        $inputs['request_offer_id'] = $requestOffer->id;
        $inputs['client_id'] = $requestOffer->client_id;

        $this->sendNotification($inputs);

        return response('success');
    }



}
