<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
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
            'rating' => 'required',
            'content' => 'nullable',
            'order_id' => ['required', 'exists:orders,id'],
        ];
    }
    public function messages()
    {
        return [
            'rating.required' => 'Please Select At Least One Star',
        ];
    }
}
