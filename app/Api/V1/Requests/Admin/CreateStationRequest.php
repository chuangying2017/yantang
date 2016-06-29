<?php

namespace App\Api\V1\Requests\Admin;

use App\Http\Requests\Request;

class CreateStationRequest extends Request {

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
            'district_id' => 'required',
            'address' => 'required',
            'cover_image' => 'required',
            'director' => 'required',
            'phone' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'geo' => 'required',
        ];
    }
}
