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
                'name' => 'required',
                'pid' => 'sometimes|exists:categories,id'
            ];
            if (is_null($this->input('pid')) || $this->input('pid') == 0) {
                $rules['pid'] = '';
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'pid' => '分类'
        ];
    }
}
