<?php

namespace App\Http\Controllers\Backend;

use App\Models\Offer;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\RequestOffer;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::count();
        $payments = Payment::count();
        $providers = Provider::count();
        $offers = Offer::count();
        $messages = ChatMessage::count();
        $request_offers = RequestOffer::count();
        return view('backend.statistic', compact(
            'clients',
            'payments',
            'providers',
            'offers',
            'request_offers',
            'messages'
        ));
    }

    /**
     * redirect page if not have permission
     */
    public function permission()
    {
        return view('backend.errors');
    }

}
