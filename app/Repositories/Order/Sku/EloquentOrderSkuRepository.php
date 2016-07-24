<?php namespace App\Repositories\Order\Sku;

use App\Models\Order\Order;
use App\Models\Order\OrderSku;
use App\Repositories\Product\ProductProtocol;
use App\Repositories\Product\Sku\ProductMixRepositoryContract;
use App\Services\Order\OrderProtocol;

class EloquentOrderSkuRepository implements OrderSkuRepositoryContract {

    public function createOrderSkus($order, $data)
    {
        $order_id = $order['id'];
        $order_skus = [];
        if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_CAMPAIGN) {
            foreach ($data as $temp_order_sku) {
                $order_skus = array_merge($order_skus, $this->createMixSku($order_id, $temp_order_sku));
            }
        } else if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_REFUND) {
            foreach ($data as $temp_order_sku) {
                $order_sku = $this->saveRefundSku($order_id, $temp_order_sku);
                $order_skus[] = $order_sku;
            }
        } else {
            foreach ($data as $temp_order_sku) {
                $order_sku = $this->saveOrderSku($order_id, $temp_order_sku);
                $order_skus[] = $order_sku;
            }
        }

        return $order_skus;
    }

    public function getOrderSkus($order_id)
    {
        return OrderSku::query()->where('order_id', $order_id)->get();
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
            'quantity' => array_get($temp_order_sku, 'quantity', 1),
            'price' => $temp_order_sku['price'],
            'discount_amount' => array_get($temp_order_sku, 'discount_amount', 0),
            'pay_amount' => $temp_order_sku['pay_amount'],
            'attr' => $temp_order_sku['attr'] ?: '',
            'type' => array_get($temp_order_sku, 'type', ProductProtocol::TYPE_OF_ENTITY),
        ]);
    }

    protected function saveRefundSku($order_id, $temp_order_sku)
    {
        $refund_sku = OrderSku::create([
            'order_id' => $order_id,
            'origin_order_id' => $temp_order_sku['id'],
            'product_sku_id' => $temp_order_sku['product_sku_id'],
            'product_id' => $temp_order_sku['product_id'],
            'name' => $temp_order_sku['name'],
            'cover_image' => $temp_order_sku['cover_image'],
            'quantity' => $temp_order_sku, 'quantity',
            'price' => $temp_order_sku['price'],
            'discount_amount' => array_get($temp_order_sku, 'discount_amount', 0),
            'pay_amount' => $temp_order_sku['pay_amount'],
            'attr' => $temp_order_sku['attr'] ?: '',
            'type' => array_get($temp_order_sku, 'type', ProductProtocol::TYPE_OF_ENTITY),
        ]);

        OrderSku::query()->where('id', $temp_order_sku['id'])->increment('return_quantity', $refund_sku['quantity']);

        return $refund_sku;
    }


    protected function createMixSku($order_id, $temp_order_mix_sku)
    {
        $order_skus = [];
        $mix_skus = app()->make(ProductMixRepositoryContract::class)->getMixSkus($temp_order_mix_sku['id']);

        foreach ($mix_skus as $sku) {
            $sku['discount_amount'] = 0;
            $sku['pay_amount'] = 0;
            $sku['quantity'] = $sku['pivot']['quantity'];
            $sku['price'] = $sku['settle_price'];
            $order_skus[] = $this->saveOrderSku($order_id, $sku);
        }

        $temp_order_mix_sku['type'] = ProductProtocol::TYPE_OF_MIX;
        $order_skus[] = $this->saveOrderSku($order_id, $temp_order_mix_sku);
        return $order_skus;
    }

    public function getOrderSkusByIds($order_sku_ids)
    {
        return OrderSku::query()->find($order_sku_ids);
    }
}
