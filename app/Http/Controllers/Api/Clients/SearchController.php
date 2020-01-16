<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\OffersCollection;

class SearchController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->lang == 'ar') {
            $name = 'name_ar';
            $description = 'description_ar';
        } else {
            $name = 'name_en';
            $description = 'description_en';
        }

        $offers = Offer::with(['provider' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'rate');
        }, 'country' => function ($query) use ($name) {
            $query->select('id', "$name as name");
        }, 'city' => function ($query) use ($name) { 
            $query->select('id', "$name as name");
        }])->select('id', 'lat', 'category_id', 'provider_id', 'hotel_level','event_name', 'from', 'to', 'end_at', 'country_id', 'city_id', 'lng', 'currency','price', 'location', 'days');

        if ($request->country_id != null) {
            $offers = $offers->where('country_id', $request->country_id);
        }

        if($request->currency != null){
            $offers = $offers->where('currency', request('currency'));
        }

        if ($request->category_id != null) {
            $offers = $offers->where('category_id', $request->category_id);
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
     
        // if ($request->price != null) {
        //     if ($request->price == 0) {
        //         $order = 'asc';
        //     } else {
        //         $order = 'desc';
        //     }
        //     $offers = $offers->orderBy('price', $order);
        // }

        if ($request->sort != null) {
            if ($request->sort == 0) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }
            $offers = $offers->orderBy('id', $order);
        }

        $offers = $offers->where('end_at', '>=', date('Y-m-d'))->where('status', 1)->orderBy('id', 'desc')->paginate(5);

        return $this->apiResponse(new OffersCollection($offers));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
