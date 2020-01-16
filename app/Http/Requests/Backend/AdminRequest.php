<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'name' => 'required|string',
            'permission_id' => 'required|integer|exists:permissions,id',
        ];

        if($this->method() == 'PATCH'){
            if($this->password != null){
                $rules['password'] = 'min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
            }
            $rules['image']    = 'image';
            $rules['email']    = 'required|unique:users,email, ' . $this->user->id;
        }else{
            $rules['password'] = 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
            $rules['image']    = 'required|image';
            $rules['email']    = 'required|unique:users,email';
        }

        return $rules;
    }
}
