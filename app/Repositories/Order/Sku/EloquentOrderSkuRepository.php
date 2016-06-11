<?php namespace App\Repositories\Order\Sku;

use App\Models\Order\OrderSku;

class EloquentOrderSkuRepository implements OrderSkuRepositoryContract {

    public function createOrderSkus($order_id, $data)
    {
        $order_skus = [];
        foreach ($data as $temp_order_sku) {
            $order_sku = OrderSku::create([
                'order_id' => $order_id,
                'origin_order_id' => $order_id,
                'product_sku_id' => $temp_order_sku['id'],
                'product_id' => $temp_order_sku['product_id'],
                'name' => $temp_order_sku['name'],
                'cover_image' => $temp_order_sku['cover_image'],
                'quantity' => $temp_order_sku['quantity'],
                'price' => $temp_order_sku['price'],
                'discount_amount' => $temp_order_sku['discount_amount'],
                'pay_amount' => $temp_order_sku['pay_amount'],
                'attr' => $temp_order_sku['attr']
            ]);
            $order_skus = $order_sku;
        }

        return $order_skus;
    }

    public function getOrderSkus($order_id)
    {
        return OrderSku::where('order_id', $order_id)->get();
    }
}
