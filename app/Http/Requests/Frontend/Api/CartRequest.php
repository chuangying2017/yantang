<?php

namespace App\Http\Requests\Frontend\Api;

use App\Http\Requests\Request;

class CartRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo @troy jwt
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
        if ($this->isMethod('POST')) {
            $rules = [
                'product_sku_id' => 'required|exists:product_sku,id',
                'quantity'       => 'required|min:1'
            ];
        }

        return $rules;
    }
}
