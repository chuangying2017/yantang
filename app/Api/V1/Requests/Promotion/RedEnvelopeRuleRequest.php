<?php

namespace App\Api\V1\Requests\Promotion;

use App\Http\Requests\Request;
use App\Repositories\RedEnvelope\RedEnvelopeProtocol;

class RedEnvelopeRuleRequest extends Request {

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
            'desc' => 'required',
            'type' => 'required|in:' . implode(',', array_keys(RedEnvelopeProtocol::type())),
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'coupons' => 'required',
            'quantity' => 'required|numeric',
            'effect_days' => 'required|numeric',
        ];
    }
}
