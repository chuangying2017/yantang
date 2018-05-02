<?php

namespace App\Api\V1\Requests;

use App\Services\Preorder\PreorderProtocol;

class SubscribeOrderRequest extends Request {

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
            'address_id' => 'required',
            'skus' => 'required',
            'weekday_type' => 'required|in:' . implode(',', array_keys(PreorderProtocol::weekdayType())),
            'daytime' => 'required|in:' . PreorderProtocol::DAYTIME_OF_AM . ',' . PreorderProtocol::DAYTIME_OF_PM,
            'start_time' => 'required|date|after:today'
        ];
    }
}
