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

    public function getSkus(Preorder $preorder)
    {
        $data = [];
        foreach ($preorder->skus as $sku) {
            $data[intval($sku['daytime'])][] =
                [
                    'weekday' => $sku['weekday'],
                    'daytime' => $sku['daytime'],
                    'product_sku_id' => $sku['product_sku_id'],
                    'name' => $sku['name'],
                    'cover_image' => $sku['cover_image'],
                    'quantity' => $sku['quantity'],
                    'price' => display_price($sku['price']),
                ];
        }

        for ($daytime = 0; $daytime <= 1; $daytime++) {
            $full[$daytime] = array_get($data, $daytime, []);
        }

        return $full;
    }

}
