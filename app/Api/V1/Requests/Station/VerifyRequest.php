<?php

namespace App\Api\V1\Requests\Station;

use App\Http\Requests\Request;

class VerifyRequest extends Request
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
            //
            'stime'=>'required|date',
            'etime'=>'required|date|after:stime',
            'staff_id'=>'nullable',
        ];
    }

    public function method()
    {
        return [
            'stime.required' => '开始时间不能为空',
            'stime.date' => '日期格式有误',
            'etime.required' => '结束时间不能为空',
            'etime.date' => '日期格式有误',
            'etime.after' => '开始时间必须大于等于结束时间'
        ];
    }
}
