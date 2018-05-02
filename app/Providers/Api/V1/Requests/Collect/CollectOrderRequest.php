<?php

namespace App\Api\V1\Requests\Collect;

use App\Api\V1\Requests\Request;
use App\Services\Preorder\PreorderProtocol;

class CollectOrderRequest extends Request {

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
        return [
            'address_id' => 'required|numeric',
            'sku_id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];
    }
}
