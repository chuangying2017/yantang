<?php

namespace App\Api\V1\Requests\Integral;

use App\Http\Requests\Request;
use App\Rules\ExampleRule\SizeNumberrule;


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
            'product_id'        =>  'required|numeric|exists:integral_product,id',
            'address.name'      =>  'required|max:30',
            'address.phone'     =>  ['required', 'regex:/^1[3|4|5|6|7|8][0-9]{9}$/'],
            'address.district'  =>  'required|max:50',
            'address.city'      =>  'required|max:20',
            'address.detail'    =>  'required|max:200',
            'address.province'  =>  'required|max:30',
            'buy_num'           =>  ['required', 'max:5', 'cn_val']
        ];
    }

    public function messages()
    {
        return [
            'address.phone.regex'       => 'mobile format error',
            'buy_num.cn_val'            => 'must be greater than one or equal one of integer',
        ];
    }
}
