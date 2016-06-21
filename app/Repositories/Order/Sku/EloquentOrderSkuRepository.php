<?php namespace App\Repositories\Order\Sku;

use App\Models\Order\OrderSku;
use App\Repositories\Product\Sku\ProductMixRepositoryContract;

class EloquentOrderSkuRepository implements OrderSkuRepositoryContract {

    public function createOrderSkus($order_id, $data)
    {
        $order_skus = [];
        foreach ($data as $temp_order_sku) {
            $order_sku = $this->saveOrderSku($order_id, $temp_order_sku);
            $order_skus[] = $order_sku;
        }

        return $order_skus;
    }

    public function getOrderSkus($order_id)
    {
        return OrderSku::where('order_id', $order_id)->get();
    }

    protected function saveOrderSku($order_id, $temp_order_sku)
    {
        return OrderSku::create([
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
    }


    #todo 带完成
    protected function createMixSku($order_id, $temp_order_sku)
    {
        $skus = app()->make(ProductMixRepositoryContract::class)->getMixSkus($temp_order_sku['id']);
        $this->saveOrderSku($order_id, $temp_order_sku);
        foreach ($skus as $sku) {
            $this->saveOrderSku($order_id, $sku);
        }
    }
}
