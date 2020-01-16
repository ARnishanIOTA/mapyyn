<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'fb'             => 'required|url',
            'tw'             => 'required|url',
            'instagram'      => 'required|url',
            'whatsapp'       => 'required|numeric',
            'email'          => 'required|email',
            'phone'          => 'required|numeric',
            'logo'           => 'image|max:3000',
            'about_ar'       => 'required|string|min:3|regex:/\p{Arabic}[1-9 ]/u',
            'about_en'       => 'required|string|min:3|regex:/^[a-zA-Z ]/',
            'terms_ar'       => 'required|string|min:3|regex:/\p{Arabic}[1-9 ]/u',
            'terms_en'       => 'required|string|min:3|regex:/^[a-zA-Z ]/',
        ];
    }
}
