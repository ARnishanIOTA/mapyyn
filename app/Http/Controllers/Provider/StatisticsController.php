<?php

namespace App\Http\Controllers\Provider;

use App\Models\Chat;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\Provider;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::where('provider_id', auth('providers')->id())->count();
        $offers   = Offer::where('provider_id', auth('providers')->id())->count();
        $messages = Chat::where('provider_id', auth('providers')->id())->count();
        $request_offers = RequestOfferProvider::where('provider_id', auth('providers')->id())->count();
        return view('providers.statistic', compact(
            'payments',
            'offers',
            'request_offers',
            'messages'
        ));
    }

}
