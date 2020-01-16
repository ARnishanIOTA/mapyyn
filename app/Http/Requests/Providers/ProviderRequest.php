<?php

namespace App\Http\Requests\Providers;

use Illuminate\Validation\Rule;
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
            'code'       => 'required|integer',
            'email'      => 'required|string|email|unique:providers,email, ' . auth('providers')->id(),
            'phone'      => 'required|numeric|unique:providers,phone, ' . auth('providers')->id(),
            'address'    => 'required|string',
            // 'country'    => 'required|integer|exists:countries,id',
            // 'city'       => 'required|exists:cities,id',
        ];

        if($this->password != null){
            $rules['password'] = 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
        }

        if($this->categories != null){
            $rules['categories'] = 'required|array|max:50|'.Rule::in([1,2,3,4]);
        }

        if($this->logo != null){
            $rules['logo'] = 'required|image|max:5000';
        }

        return $rules;
    }
}
