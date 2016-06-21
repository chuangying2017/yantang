<?php namespace App\Api\V1\Requests\Subscribe;

use App\Api\V1\Requests\Request;


/**
 * Class RegisterRequest
 * @package App\Api\V1\Requests\Frontend\Access
 */
class PreorderProductRequest extends Request
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
     * weekdays {"1":{"daytime":1,"skus":{"1":{"sku_name":"a","count":3},"2":{"sku_name":"b","count":5}}},"2":{"daytime":0,"skus":{"1":{"sku_name":"a","count":3},"2":{"sku_name":"b","count":5}}}}
     *
     * @return array
     */
    public function rules()
    {
        return [
            'preorder_id' => 'required',
            'weekdays' => 'required',
//            'sku' => 'required',
//            'daytime' => 'required',
        ];
    }
}
