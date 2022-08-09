<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirm' => 'required|same:password',
            'token' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password_confirm.required' => __('validation.required', ['attribute' => 'confirm password']),
            'token.required' => __('validation.required', ['attribute' => 'OTP']),
            'password_confirm.same' => __('passwords.cormfirm_not_match')
        ];
    }
}
