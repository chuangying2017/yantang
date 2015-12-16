<?php

namespace App\Http\Requests\Frontend\Api;

use App\Http\Requests\Request;

class MarketingRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo @troy auth
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //
        ];

        if ($this->route()->getName() == 'api.marketing.coupons.store') {
            $rules = [
                'resource_id' => 'required'
            ];
        }

        if ($this->route()->getName() == 'api.marketing.coupons.exchange') {
            $rules = [
                'ticket_id' => 'required',
                'uuid'      => 'required'
            ];
        }

        return $rules;
    }
}
