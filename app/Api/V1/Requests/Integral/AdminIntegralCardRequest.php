<?php

namespace App\Api\V1\Requests\Integral;

use App\Http\Requests\Request;

class AdminIntegralCardRequest extends Request
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
            'name'                  =>  'required|max:50',
            'give'                  =>  'required|numeric',//赠送积分
            'status'                =>  'sometimes|max:30',
            'type'                  =>  'sometimes|max:30',
            'mode'                  =>  'sometimes|numeric',
            'cover_image'           =>  'url',
            'issue_num'             =>  'required|numeric|min:1',
            'remain'                =>  'nullable|numeric',
            'get_member'            =>  'nullable',
            'draw_num'              =>  'required|numeric|min:1',
            'start_time'            =>  'required|date|after:today',
            'end_time'              =>  'required|date|after:start_time'

        ];
    }

}
