<?php

namespace App\Http\Requests\Backend\Api;

use App\Http\Requests\Request;

class BrandRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo @troy 权限检查
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
                'name' => 'required'
            ];
        }

        if ($this->route()->getName() == 'brands.bind.category') {
            $rules = [
                'brand_id'    => 'required',
                'category_id' => 'required'
            ];
        }

        return $rules;
    }
}
