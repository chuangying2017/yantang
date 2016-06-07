<?php namespace App\Http\Transformers;

use App\Models\Order\OrderSku;
use App\Models\OrderRefund;
use League\Fractal\TransformerAbstract;

class OrderRefundTransformer extends TransformerAbstract {


    public function transform(OrderRefund $refund)
    {
        return [
            'id'            => (int)$refund->id,
            'order_id'      => $refund->order_id,
            'amount'        => display_price($refund->amount),
            'user_id'       => $refund->user_id,
            'status'        => $refund->status,
            'company_name'  => $refund->company_name,
            'post_no'       => $refund->post_no,
            'client_memo'   => $refund->client_memo,
            'merchant_memo' => $refund->merchant_memo,
            'deliver_at'    => $refund->deliver_at,
            'refund_at'     => $refund->refund_at,
            'created_at'    => $refund->created_at,
            'updated_at'    => $refund->updated_at,
        ];
    }
}
