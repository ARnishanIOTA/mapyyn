<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'email'    => 'required|string|email|unique:clients,email, ' . auth('apiClient')->id(),
            'phone'    => 'required|numeric|unique:clients,phone, ' . auth('apiClient')->id(),
            'country'  => 'required|string',
            'city'     => 'required|string',
            'code'     => 'required|integer',
            'lang'     => 'required'
        ];

        if($this->password != null){
            $rules['password'] = 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
        }

        return $rules;
    }
}
