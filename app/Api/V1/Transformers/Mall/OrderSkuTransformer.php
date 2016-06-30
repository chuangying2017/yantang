<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Order\OrderSku;
use League\Fractal\TransformerAbstract;

class OrderSkuTransformer extends TransformerAbstract {

    public function transform(OrderSku $sku)
    {
        return [
            'order' => ['id' => $sku['order_id']],
            'sku' => [
                'id' => $sku['product_sku_id'],
                'product_id' => $sku['product_id']
            ],
            'quantity' => $sku['quantity'],
            'price' => display_price($sku['price']),
            'discount_amount' => display_price($sku['discount_amount']),
            'pay_amount' => display_price($sku['pay_amount']),
            'name' => $sku['name'],
            'cover_image' => $sku['cover_image'],
            'attr' => $sku['attr'],
        ];
    }

}
