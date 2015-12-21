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
                'basic_info.brand_id'    => 'required',
                'basic_info.merchant_id' => 'required|exists:merchants,id',
                'basic_info.title'       => 'required',
                'basic_info.price'       => 'required|numeric',
                'basic_info.open_status' => 'required|in:' . implode(',', array_keys(ProductConst::openStatus())),
                'basic_info.attributes'  => 'required|array',
                'basic_info.detail'      => 'required',
                'skus'                   => 'required|array',
                'images_ids'             => 'array',
                'group_ids'              => 'array'
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
