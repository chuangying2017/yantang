<?php

namespace App\Api\V1\Requests\Integral;

use App\Http\Requests\Request;

class OrderGenerateRequest extends Request
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
            'address.name'      =>  'required|max:20|min:3',
            'address.phone'     =>  'regex:["/^1[3|4|5|6|7|8][0-9]\d{4,8}$/"]',
            'address.province'  =>  'required|max:20',
            'address.city'      =>  'required|max:20',
            'address.district'  =>  'required|max:50',
            'address.detail'    =>  'required|max:100',
            'product_id'        =>  'required|numeric|max:5',
            'buy_num'           =>  'required|numeric|max:5',
            'product_name'      =>  'required',
            'product_integral'  =>  'required|numeric',
        ];
    }
}
