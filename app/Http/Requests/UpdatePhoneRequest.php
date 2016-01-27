<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdatePhoneRequest extends Request {

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
        $token = $this->input('uuid', null);
        return [
            'phone' => 'required|confirm_mobile_not_change:' . $token,
        ];
    }
}
