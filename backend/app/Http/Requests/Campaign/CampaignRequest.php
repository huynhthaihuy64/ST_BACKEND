<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
            'address' => 'required|max:100',
            'status' => 'required',
            'image' => 'required',
            'quantity' => 'required|integer',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'description' => 'nullable',
            'sheet_id' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' =>  __('validation.required', ['attribute' => 'Name']),
            'address.required' => __('validation.required', ['attribute' => 'Address']),
            'status.required' => __('validation.required', ['attribute' => 'Status']),
            'image.required' => __('validation.required', ['attribute' => 'Image']),
            'quantity.required' => __('validation.required', ['attribute' => 'Quantity']),
            'start_at.required' => __('validation.required', ['attribute' => 'Start at']),
            'end_at.required' => __('validation.required', ['attribute' => 'End at']),
        ];
    }
}
