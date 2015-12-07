<?php

namespace App\Http\Requests\Backend\Api;

use App\Http\Requests\Request;

class CategoryRequest extends Request {

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
        $rules = [];

        if ($this->isMethod('POST') || $this->isMethod('PUT')) {
            $rules = [
                'name' => 'required',
                'pid'  => 'exists:categories:id'
            ];
        }

        return $rules;
    }
}
