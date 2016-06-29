<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use App\Api\V1\Transformers\Subscribe\Preorder\PreorderSkuTransformer;
use App\Models\Subscribe\Preorder;
use League\Fractal\TransformerAbstract;

class StaffPreorderTransformer extends TransformerAbstract {


    public function transform(Preorder $order)
    {

        $data = [
            'id' => $order->id,
            'skus' => $this->getSkus($order),
            'name' => $order->name,
            'order_no' => $order->order_no,
            'phone' => $order->phone,
            'address' => $order->address,
            'district' => ['id' => $order->district_id],
            'status' => $order->status,
            'charge_status' => $order->charge_status,
            'start_time' => $order->start_time,
            'end_time' => $order->end_time,
            'created_at' => $order->created_at,
        ];

        return $data;
    }

    public function getSkus(Preorder $order)
    {
        $skus = [];
        foreach ($order->skus as $sku) {
            $skus[] = [
                'name' => $sku['name'],
                'cover_image' => $sku['cover_image'],
                'price' => $sku['price'],
                'quantity' => $sku['quantity'],
                'total_amount' => $sku['total_amount'],
            ];
        }

        return $skus;
    }

}
