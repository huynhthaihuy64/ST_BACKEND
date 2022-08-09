<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'status' => 'required',
            'cv' => 'required',
            'description' => 'nullable',
            'avatar' => 'nullable',
            'other' => 'nullable',
        ];
    }

    /**
     * The messages validation to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => __('validation.required', ['attribute' => 'email']), 
            'email.email' => __('validation.email', ['attribute' => 'email']),                
            'name.required' => __('validation.required', ['attribute' => 'name']),
            'phone.required' => __('validation.required', ['attribute' => 'phone']),
            'phone.regex' => __('validation.regex', ['attribute' => 'Phone']),
            'status.required' => __('validation.required', ['attribute' => 'status']),
            'cv.required' => __('validation.required', ['attribute' => 'cv']),
        ];
    }
}
