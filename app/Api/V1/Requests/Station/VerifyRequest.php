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
        return false;
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
            'etime'=>'required|date',
            'staff_id'=>'nullable',
        ];
    }
}
