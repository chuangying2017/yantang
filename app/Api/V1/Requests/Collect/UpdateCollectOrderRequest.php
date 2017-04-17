<?php namespace App\Api\V1\Requests\Collect;

use App\Api\V1\Requests\Request;


/**
 * Class RegisterRequest
 * @package App\Api\V1\Requests\Frontend\Access
 */
class UpdateCollectOrderRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $collect_order = $this->route('collect_order');
        return ($collect_order->staff_id == access()->id()) || $collect_order->has_generated_order;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku_id' => 'numeric',
            'address_id' => 'numeric',
            'quantity' => 'numeric',
        ];
    }
}
