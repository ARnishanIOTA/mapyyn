<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RequestOfferRequest extends FormRequest
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
            'from_country' => 'required|integer|exists:countries,id',
            'from_city'  => 'required|string',
            'to_country' => 'required|integer|exists:countries,id',
            'to_city'    => 'required|string',
            'go_date'    => 'required|date',
            'back_date'  => 'required|date|after:go_date',
            'trip_stop'  => ['required', Rule::in(['yes', 'no'])],
            'transport'  => 'required|string',
            'hotel_level' => 'required|integer|min:1|max:10', 
            'category_id'  => ['required', Rule::in([1,2,3,4])],
            'adult'      => 'required|integer|min:1|max:9000',
            'children' => 'integer|max:9000',
            'babies' => 'integer|max:9000',
            'reply_time' => 'required|integer|min:1|max:31',
            'price' => 'required|string',
            'change_date' => 'required|string',
            'interests' => 'array',
        ];

        if($this->category_id == 3)
        {
            $rules['league'] = 'required|string';
            $rules['event'] = 'required|string';
        }
        
        return $rules;    
    }
}
