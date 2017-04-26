<?php namespace App\Api\V1\Transformers\Invoice;

use App\Models\Invoice\StationInvoiceCollectOrder;
use League\Fractal\TransformerAbstract;

class StationInvoiceCollectOrderTransformer extends TransformerAbstract {

    public function transform(StationInvoiceCollectOrder $order)
    {
        return [
            'id' => $order['id'],
            'invoice_no' => $order['invoice_no'],
            'order_id' => $order['order_id'],
            'order_no' => $order['order_no'],
            'collect_order_id' => $order['collect_order_id'],
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
            'sku' => [
                'id' => $order['sku']['id'],
                'name' => $order['sku']['name'],
            ],
            'total_amount' => display_price($order['total_amount']),
            'discount_amount' => display_price($order['discount_amount']),
            'pay_amount' => display_price($order['pay_amount']),
            'service_amount' => display_price($order['service_amount']),
            'receive_amount' => display_price($order['receive_amount']),
            'pay_at' => $order['pay_at'],
        ];
    }

}
