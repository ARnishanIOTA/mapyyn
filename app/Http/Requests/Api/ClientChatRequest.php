<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientChatRequest extends FormRequest
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
        return [
            'type'             => 'required|integer|'.Rule::in([1,2]),
            'offer_type'       => 'required|integer|'.Rule::in([1,2]),
            'provider_id'      => 'required_if:type,2|exists:providers,id',
            'offer_id'         => 'required_if:offer_type,1|integer|exists:offers,id',
            'request_offer_id' => 'required_if:offer_type,2|integer|exists:request_offers,id',
        ];
    }
}
