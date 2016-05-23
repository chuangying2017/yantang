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
     * sku [{"sku_id":"1","sku_name":"a","count":"b"}]
     *
     * @return array
     */
    public function rules()
    {
        return [
            'preorder_id' => 'required',
            'weekday' => 'required',
            'sku' => 'required',
            'daytime' => 'required',
        ];
    }
}
