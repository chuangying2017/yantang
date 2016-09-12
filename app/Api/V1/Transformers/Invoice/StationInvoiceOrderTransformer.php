<?php namespace App\API\V1\Transformers\Invoice;

use App\Models\Invoice\StationInvoiceOrder;
use League\Fractal\TransformerAbstract;

class StationInvoiceOrderTransformer extends TransformerAbstract {

    public function transform(StationInvoiceOrder $order)
    {
        return [
            'id' => $order['id'],
            'invoice_no' => $order['invoice_no'],
            'order_id' => $order['order_id'],
            'preorder_id' => $order['preorder_id'],
            'order_no' => $order['order_no'],
            'status' => $order['status'],
            'name' => $order['name'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'detail' => json_decode($order['detail'], true),
            'station' => [
                'id' => $order['station_id'],
                'name' => $order['station_name'],
            ],
            'staff' => [
                'id' => $order['staff_id'],
                'name' => $order['staff_name'],
            ],
            'total_amount' => display_price($order['total_amount']),
            'discount_amount' => display_price($order['discount_amount']),
            'pay_amount' => display_price($order['pay_amount']),
            'service_amount' => display_price($order['service_amount']),
            'receive_amount' => display_price($order['receive_amount']),
        ];
    }

}
