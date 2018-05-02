<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\Request;

class FavRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo @troy auth
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
                'product_id' => 'required|exists:products,id'
            ];
        }

        return $rules;
    }
}
