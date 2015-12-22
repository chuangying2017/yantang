<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MerchantRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo auth;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //
        ];

        if ($this->isMethod('POST')) {
            $rules = [
                'name'     => 'required',
                'phone'    => 'required',
                'email'    => 'required|unique:users,email',
                'director' => 'required',
                'password' => 'required'
            ];
        }

        return $rules;
    }
}
