<?php

namespace App\Http\Controllers\Backend;

use App\Models\Payment;
use App\Models\RequestOffer;
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
        return view('backend.request-offers.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {

        $ids = RequestOffer::pluck('id');
        $payments = Payment::whereIn('request_offer_id', $ids)->pluck('request_offer_id')->toArray();

        $offers = RequestOffer::with('client', 'provider', 'providerOffer');

        return Datatables::of($offers)
            ->editColumn('client_id', function ($offer) {
                    return $offer->client->first_name . ' ' . $offer->client->last_name;
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
            })->editColumn('provider', function ($offer) {
                if($offer->provider != null){
                    return $offer->provider->first_name . ' ' . $offer->provider->last_name;
                }else{
                    return '-----';
                }
            })->editColumn('status', function ($offer) {
                return trans("lang.$offer->status");
            })->editColumn('transport', function ($offer) {
                if($offer->transport == 'home'){
                    return trans('lang.home'); 
                }elseif($offer->transport == 'way'){
                    return trans('lang.way');
                }else{
                    return trans('lang.both');
                }      
            })->editColumn('description', function ($offer) {
                return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#m_modal_'.$offer->id.'">'.trans('lang.description').'</button>
                <div class="modal fade show" id="m_modal_'.$offer->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-body">
										<p>'.$offer->description.'</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">'.trans('lang.close').'</button>
									</div>
								</div>
							</div>
						</div>';
            })
            ->editColumn('is_active', function ($offer) use($payments){
                if(in_array($offer->id, $payments)){
                    return '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.active').'</span>';
                }else{
                    if($offer->is_active == 0){
                        return '<a href="'.url('/backend/request_offers').'/is_active/'.$offer->id.'" class="btn btn-warning m-btn m-btn--icon">'.trans('lang.nonactive').'</a>';
                    }else{
                        return '<a href="'.url('/backend/request_offers').'/is_active/'.$offer->id.'" class="btn btn-success m-btn m-btn--icon">'.trans('lang.active').'</a>';
                    }
                }
                
            })
            ->addColumn('from_country', function ($offer) {
                $country = 'from_country_'.LaravelLocalization::setLocale();
                return $offer->$country;    
            })
            ->addColumn('to_country', function ($offer) {
                $country = 'to_country_'.LaravelLocalization::setLocale();
                return $offer->$country;    
            })
            ->addColumn('action', function ($offer) {
                return '
                <a href="'. url('/') . '/backend/request_offers/details/' .$offer->id.'" class="btn btn-success m-btn m-btn--icon">
                <span>'.trans('lang.details').' </span></span></a>
                <a href="'. url('/') . '/backend/request_offers/offers/show/' .$offer->id.'" class="btn btn-info m-btn m-btn--icon">
                <span>'.trans('lang.offers').' </span></span></a>';
            })
            ->rawColumns(['action', 'description', 'is_active'])
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
        $requestOffer = $requestOffer->load('interests', 'client');
        return view('backend.request-offers.details', compact('requestOffer'));
    }



     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showOffers(RequestOffer $requestOffer)
    {
        $offer = $requestOffer;
        return view('backend.request-offers.offers', compact('offer'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function offersData(RequestOffer $requestOffer)
    {
        $offer =  $requestOffer->load(['providerOffer' => function($query){
            $query->where('price', '!=', null);
        }, 'providerOffer.provider']);

        return Datatables::of($offer->providerOffer)
            ->editColumn('status', function ($offer) {
                return trans("lang.$offer->status");
            })
            ->editColumn('provider.first_name', function ($offer) {
                return optional($offer->provider)->first_name . ' ' . optional($offer->provider)->last_name;
            })
            ->editColumn('description', function ($offer) {
                return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#m_modal_'.$offer->id.'">'.trans('lang.description').'</button>
                <div class="modal fade show" id="m_modal_'.$offer->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>'.$offer->description.'</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">'.trans('lang.close').'</button>
                            </div>
                        </div>
                    </div>
                </div>';
            })->editColumn('image', function ($offer) {
                if($offer->image != null){
                    return '<img src="'.asset("uploads/$offer->image").'" height="100" width="100">' ;

                }else{
                    return '----';
                }
            })
            ->addColumn('from_country', function ($offer) {
                $country = 'from_country_'.LaravelLocalization::setLocale();
                return $offer->$country;    
            })
            ->addColumn('to_country', function ($offer) {
                $country = 'to_country_'.LaravelLocalization::setLocale();
                return $offer->$country;    
            })
            ->editColumn('reason', function ($offer) {
                if($offer->reason != null){
                    return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#m_modal_'.$offer->id.'_'.$offer->id.'">'.trans('lang.reason').'</button>
                <div class="modal fade show" id="m_modal_'.$offer->id.'_'.$offer->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>'.$offer->reason.'</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">'.trans('lang.close').'</button>
                            </div>
                        </div>
                    </div>
                </div>';

                }else{
                    return '----';
                }
            })
           
            ->rawColumns(['description', 'image', 'reason'])
            ->make(true);
    }

/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestOffer $requestOffer
     * @return \Illuminate\Http\Response
     */
    public function update(RequestOffer $requestOffer)
    {
        if($requestOffer->is_active == 1){
            RequestOfferProvider::where('request_offer_id', $requestOffer->id)->update(['is_active' => 0]);
            $requestOffer->is_active = 0;
            $requestOffer->save();
        }else{
            RequestOfferProvider::where('request_offer_id', $requestOffer->id)->update(['is_active' => 1]);
            $requestOffer->is_active = 1;
            $requestOffer->save();
        }

        return back();
    }

}
