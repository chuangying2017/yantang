<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\Request;

class AddressRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo
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

        if ($this->isMethod('POST') || $this->isMethod('PUT')) {
            $rules = [
                'data.name'     => 'required',
                'data.phone'    => 'required_without:tel"',
                'data.province' => 'required',
                'data.city'     => 'required',
                'data.detail'   => 'required',
                'data.tel'      => 'required_without:phone"'
            ];
        }

        return $rules;
    }
}
