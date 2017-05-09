<?php namespace App\Api\V1\Requests\Collect;

use App\Services\Order\OrderProtocol;
use App\Api\V1\Requests\Request;


/**
 * Class RegisterRequest
 * @package App\Api\V1\Requests\Frontend\Access
 */
class DeleteCollectOrderRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $collect_order = $this->route('collect_order');

        return is_null($collect_order->order) || ($collect_order->order->status == OrderProtocol::PAID_STATUS_OF_UNPAID);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
