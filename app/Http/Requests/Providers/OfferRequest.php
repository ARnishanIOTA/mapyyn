<?php

namespace App\Http\Requests\Providers;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'category_id'     => ['required', Rule::in([1,2,3,4])],
            'country_id'      => 'required|integer|exists:countries,id',
            'city_id'         => 'required',
            'description_ar'  => 'required|string',
            'location'        => 'required',
            'description_en'  => 'required|string',
            'hotel_level'     => 'required|integer|min:1',
            'from'            => 'required|date|after:'. date('Y-m-d'),
            'to'              => 'required|date|after:from',
            'days'            => 'required|integer|min:1|max:356',
            'end_at'          => 'required|date|before_or_equal:to|after_or_equal:'.date('Y-m-d'),
            'persons'         => 'required|integer|min:1',
            'transport'       => ['required', Rule::in(['home', 'way', 'both'])],
            'images'          => 'required|array|max:5',
            'images.*'        => 'image',
            'price'           => 'required',
            'currency'        => 'required',
            'lat'             => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng'             => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];

        if($this->category_id == 3){
            $rules['league_id'] = 'required|integer';
            $rules['event_id']  = 'required|integer';
        }

        return $rules;
    }
}
