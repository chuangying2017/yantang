<?php

namespace App\Http\Requests\Backend\Api;

use App\Http\Requests\Request;

class AttributeRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo @troy
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

        if ($this->isMethod('POST') || $this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'name' => 'required'
            ];
        }

        if ($this->route()->getName() == 'attribute.bind.categories') {
            $rules = [
                'category_id' => 'required'
            ];
        }

        return $rules;
    }
}
