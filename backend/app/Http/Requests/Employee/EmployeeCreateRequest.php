<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
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
            'address' => 'nullable',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'birthday' => 'nullable',
            'experience' => 'nullable',
            'cv' => 'required',
            'description' => 'nullable',
            'avatar' => 'nullable',
            'status' => 'nullable',
            'start_date' => 'nullable|date',
            'technologies' => 'nullable',
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
            'phone.required' => __('validation.required', ['attribute' => 'phone']),
            'phone.regex' => __('validation.regex', ['attribute' => 'Phone']),
            'cv.required' => __('validation.required', ['attribute' => 'cv']),
        ];
    }
}
