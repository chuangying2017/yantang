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

            if ($this->input('reset')) {
                $rules['phone'] .= '|exists:users,phone';
            } else {
                if ( ! access()->user()) {
                    $rules['phone'] .= '|unique:users,phone';
                }
            }
        }

        return $rules;
    }
}
