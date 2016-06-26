<?php

namespace App\Api\V1\Requests\Campaign;

use App\Http\Requests\Request;
use App\Repositories\Store\StoreRepositoryContract;

class BindStoreRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(StoreRepositoryContract $storeRepo)
    {
        return $storeRepo->getBindToken($this->route()->getParameter('store_id')) == $this->input('bind_token');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bind_token' => 'required'
        ];
    }
}
