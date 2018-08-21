<?php

namespace App\Api\V1\Requests\Integral;

use App\Http\Requests\Request;

class SignRequest extends Request
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
            'date'  => 'sometimes|date_format:Y-m-d'
        ];
    }

    public function messages()
    {
        return [
            'date.date_format'  =>  '日期格式错误'
        ];
    }
}
