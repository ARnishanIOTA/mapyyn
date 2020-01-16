<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\CountriesCollection;

class CountryController extends ApiController
{
    /**
     * gel all cities for spacific country
     *
     * @param object $request
     * @return json
     */
    public function countries(Request $request)
    {
        $name = request('lang') == 'ar' ? 'name_ar' : 'name_en';
        $countries = Country::select('id', "$name as name", 'latitude', 'longitude', 'phonecode', 'sortname')->get();

        return $this->apiResponse(new CountriesCollection($countries));
    }
}
