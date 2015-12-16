<?php

namespace App\Http\Requests\Backend\Api;

use App\Http\Requests\Request;
use App\Services\Marketing\MarketingProtocol;

class MarketingCouponRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo @troy check permission
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->isMethod('POST') | $this->isMethod('PUT')) {
            $rules = [
                'name'              => 'required',
                'enable'            => 'sometimes|in:' . MarketingProtocol::DISCOUNT_ENABLE . ',' . MarketingProtocol::DISCOUNT_DISABLE,
                'quantity_per_user' => 'sometimes|min:1',
                'type'              => 'required|in:' . implode(',', MarketingProtocol::discountType(null, true)),
                'content'           => 'required|integer|min:1',
                'detail'            => 'required',
                'quantity'          => 'required|integer|min:1',
                'amount'            => 'required|integer|min:1',
                'multi'             => 'required|boolean',
                'effect_time'       => 'date',
                'expire_time'       => 'date|after:effect_time'
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [

        ];
    }
}
