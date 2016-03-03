<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SmsRequest extends Request {

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

        $rules = [];
        if ($this->isMethod('POST')) {
            $rules = [
                'phone' => 'required|zh_mobile'
            ];
            if ( ! get_current_auth_user()) {
                $rules['phone'] .= 'required|unique:users,phone';
            }
        }

        return $rules;
    }
}
