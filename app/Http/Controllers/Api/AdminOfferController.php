<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminOffer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminOfferController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = AdminOffer::where('from', '<=', date('Y-m-d'))->where('to', '>=', date('Y-m-d'))->get();

        return $this->apiResponse($offers);
        
    }
}
