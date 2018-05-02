<?php

namespace App\Api\V1\Requests\Admin\Preorders;

use App\Http\Requests\Request;

class UpdateAssignRequest extends Request {

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
            'station' => 'required|exists:stations,id'
        ];
    }
}
