<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Resources\CitiesCollection;
use App\Http\Controllers\Api\ApiController;

class CityController extends ApiController
{
    /**
     * gel all cities for spacific country
     * 
     * @param object $request
     * @return json
     */
    public function cities(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $name = request('lang') == 'ar' ? 'name_ar' : 'name_en';
        $cities = City::select('id', "$name as name")->where('country_id', request('country_id'))->get();
        

        return $this->apiResponse(new CitiesCollection($cities));
    }
}
