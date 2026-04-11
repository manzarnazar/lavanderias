<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'address_name'   => ['required', 'string', 'max:256'],

            'road_no'        => ['nullable', 'string', 'max:100'],
            'house_no'       => ['nullable', 'string', 'max:50'],
            'house_name'     => ['nullable', 'string', 'max:100'],
            'area'           => ['required', 'string', 'max:150'],
            'flat_no'        => ['nullable', 'string', 'max:50'],

            'sub_district_id' => ['nullable', 'integer', 'exists:sub_districts,id'],
            'district_id'    => ['nullable', 'integer', 'exists:districts,id'],

            'address_line'   => ['nullable', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
            'delivery_note'  => ['nullable', 'string', 'max:255'],

            'post_code'      => ['nullable', 'string', 'max:20'],
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
            'phone_number'    => ['required', 'string', 'max:20','regex:/^[0-9+_]+$/'],
        ];
    }
}
