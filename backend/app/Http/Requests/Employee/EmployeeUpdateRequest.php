<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
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
            'position_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'experience' => 'required',
            'cv' => 'required',
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
            'position_id.required' => __('validation.required', ['attribute' => 'position_id']),
            'name.required' => __('validation.required', ['attribute' => 'name']),
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.email' => __('validation.email', ['attribute' => 'email']),
            'address.required' => __('validation.required', ['attribute' => 'address']),
            'phone.required' => __('validation.required', ['attribute' => 'phone']),
            'phone.regex' => __('validation.regex', ['attribute' => 'Phone']),
            'experience.required' => __('validation.required', ['attribute' => 'experience']),
            'cv.required' => __('validation.required', ['attribute' => 'cv']),
            'avatar.required' => __('validation.required', ['attribute' => 'avatar']),
        ];
    }
}
