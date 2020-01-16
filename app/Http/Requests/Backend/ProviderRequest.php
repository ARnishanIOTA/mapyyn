<?php

namespace App\Http\Requests\Backend;

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
        $rules =  [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'code'       => 'required|integer',
            'address'    => 'required|string',
            'is_active'  => 'required|boolean',
            'categories' => 'required|array'
        ];

        if($this->method() == 'POST'){
            $rules['country']    = 'required|exists:countries,id';
            $rules['city']       = 'required|string';
            $rules['logo'] = 'required|image|max:5000';
            $rules['email'] = 'required|string|email|unique:providers,email';
            $rules['phone'] = 'required|numeric|unique:providers,phone';
            $rules['password'] = 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
        }else{
            $rules['logo'] = 'image|max:5000';
            $rules['email'] = 'required|string|email|unique:providers,email, ' . $this->provider->id;
            if($this->password != null){
                $rules['password'] = 'min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
            }
        }

        return $rules;
    }
}
