<?php

namespace App\Api\V1\Requests\Subscribe;

use App\Http\Requests\Request;

class CreateOrUpdatePreorderRequest extends Request {

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
            'phone' => 'required',
            'address' => 'required',
            'district' => 'required|exists:districts,id',
            'longitude' => 'required',
            'latitude' => 'required',
//            'station' => 'required',
        ];
    }
}
