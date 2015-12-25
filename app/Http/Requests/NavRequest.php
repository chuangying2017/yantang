<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class NavRequest extends Request
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
        $rules = [
            //
        ];

        if ($this->isMethod('POST') || $this->isMethod('PUT')) {
            $rules = [
                'name' => 'required',
                'type' => 'required',
                'url' => 'required',
            ];
        }

        return $rules;
    }
}
