<?php

namespace App\Api\V1\Requests\Station;

use App\Http\Requests\Request;

class AssignStaffRequest extends Request {

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
            'staff' => 'required|exists:station_staffs,id'
        ];
    }
}
