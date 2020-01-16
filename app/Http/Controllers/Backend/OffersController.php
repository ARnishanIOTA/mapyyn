<?php

namespace App\Http\Controllers\Backend;

use App\Models\Offer;
use App\Models\Payment;
use App\Models\Provider;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Provider $provider)
    {
        return view('backend.providers.offers.index', compact('provider'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData(Provider $provider)
    {
        $ids = Offer::where('provider_id', $provider->id)->pluck('id');
        $payments = Payment::whereIn('offer_id', $ids)->pluck('offer_id')->toArray();

        $offers = Offer::with('country', 'city')->where('provider_id', $provider->id);

        $url = url('/backend/providers/offers');
        return Datatables::of($offers)
            ->addColumn('action', function ($offer) use($url) {
                return '
                <a href="'.$url . '/details/' .$offer->id.'" class="btn btn-success m-btn m-btn--icon">
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
            ->editColumn('status', function ($offer) use($url,$payments){

                if(in_array($offer->id, $payments)){
                    return '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.active').'</span>';
                }else{
                    if($offer->status == 0){
                        return '<a href="'.$url.'/status/'.$offer->id.'" class="btn btn-warning m-btn m-btn--icon">'.trans('lang.nonactive').'</a>';
                    }else{
                        return '<a href="'.$url.'/status/'.$offer->id.'" class="btn btn-success m-btn m-btn--icon">'.trans('lang.active').'</a>';
                    }
                }
                
            })
            ->editColumn('city_id', function ($offer) {
                if($offer->city != null){
                    if(LaravelLocalization::getCurrentLocale() == 'ar'){
                        $name = 'name_ar';
                    }else{
                        $name = 'name_en';
                    }
                    return $offer->city->$name;
                }
            })
            ->editColumn('currency', function ($offer) {
                return trans("lang.$offer->currency");
            })
            ->rawColumns(['action', 'status'])
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy($offer)
    {
        $clientOffer = Payment::where('offer_id', $offer)->first();

        if($clientOffer == null){
            $offer = Offer::find($offer);
            $offer->delete();
            return response('success');
        }
        abort(422);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Offer $offer)
    {
        if($offer->status == 1){
            $offer->status = 0;
            $offer->save();
        }else{
            $offer->status = 1;
            $offer->save();
        }

        return back();
    }



}
