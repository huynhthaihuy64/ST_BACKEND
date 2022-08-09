<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileCreateRequest extends FormRequest
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
            'campaign_id' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'cv' => 'required',
            'description' => 'nullable',
            'avatar' => 'nullable',
            'status' => 'nullable',
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
            'email.required' => __('validation.required', ['attribute' => 'Email']), 
            'email.email' => __('validation.email', ['attribute' => 'Email']),                
            'name.required' => __('validation.required', ['attribute' => 'Name']),
            'phone.required' => __('validation.required', ['attribute' => 'Phone']),
            'phone.regex' => __('validation.regex', ['attribute' => 'Phone']),
            'campaign_id.required' => __('validation.required', ['attribute' => 'Campaign Id']),
            'cv.required' => __('validation.required', ['attribute' => 'CV']),
        ];
    }
}
