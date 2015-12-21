<?php

namespace App\Http\Requests\Backend\Api;

use App\Http\Requests\Request;
use App\Services\Product\ProductConst;

class ProductRequest extends Request {

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
                'data.brand_id'    => 'required',
                'data.title'       => 'required',
                'data.price'       => 'required|numeric',
                'data.open_status' => 'required|in:' . implode(',', array_keys(ProductConst::openStatus())),
                'data.attributes'  => 'required|array',
                'data.detail'      => 'required',
                'data.skus'        => 'required|array',
                'data.images_ids'  => 'array',
                'data.group_ids'   => 'array'
            ];
        }

        if ($this->route()->getName() == 'api.products.operate') {
            $rules = [
                'action'      => 'required|in:' . ProductConst::VAR_PRODUCT_STATUS_UP . ',' . ProductConst::VAR_PRODUCT_STATUS_DOWN,
                'products_id' => 'required'
            ];
        }

        return $rules;
    }
}
