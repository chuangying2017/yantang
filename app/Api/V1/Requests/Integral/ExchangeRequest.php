<?php

namespace App\Api\V1\Requests\Integral;

use App\Http\Requests\Request;

class ExchangeRequest extends Request
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
            'cost_integral'     =>  'required|numeric|min:1',
            'promotions_id'     =>  'required|numeric|min:1',
            'valid_time'        =>  'required|date|after:today',
            'deadline_time'     =>  'required|date|after:valid_time',
            'status'            =>  'sometimes|numeric',
            'type'              =>  'sometimes|string',
            'issue_num'         =>  'required|numeric|min:1',
            'draw_num'          =>  'sometimes|numeric',
            'remain_num'        =>  'sometimes|numeric',
            'delayed'           =>  'required|numeric|min:1',
            'cover_image'       =>  'sometimes|url',
            'member_type'       =>  'required|numeric',
            'limit_num'         =>  'required|numeric|min:1',
            'name'              =>  'required|min:2',
            'description'       =>  'sometimes|min:2',
        ];
    }
}
