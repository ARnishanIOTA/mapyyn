<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'    => 'required|string|email|unique:providers,email, ' . auth('apiProvider')->id(),
            'phone'    => 'required|numeric|unique:providers,phone, ' . auth('apiProvider')->id(),
            'country'  => 'required|string',
            'address'  => 'required|string',
            'lang'     => 'required',
            'code'     => 'required|integer'
        ];

        if($this->categories != null){
            $rules['categories'] = 'required|array|max:50|'.Rule::in([1,2,3,4]);
        }

        return $rules;
    }
}
