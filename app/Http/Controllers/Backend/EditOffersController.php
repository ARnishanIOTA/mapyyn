<?php

namespace App\Http\Controllers\Backend;

use App\Models\Offer;
use App\Models\Provider;
use App\Models\EditOffer;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EditOffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Provider $provider)
    {
        return view('backend.providers.edit-offers.index', compact('provider'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData(Provider $provider)
    {
        $offers = Offer::where('provider_id', $provider->id)->pluck('id');
        $offers = EditOffer::with('client')->whereIn('offer_id', $offers);
        $url = url('/backend/providers/offers');
        return Datatables::of($offers)
            ->addColumn('action', function ($offer) use($url) {
                return '
                <a href="'.$url . '/details/' .$offer->offer_id.'" class="btn btn-success m-btn m-btn--icon">
                <span>'.trans('lang.origin_offer').' </span></span></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return view('backend.providers.offers.details', compact('offer'));
    }



}
