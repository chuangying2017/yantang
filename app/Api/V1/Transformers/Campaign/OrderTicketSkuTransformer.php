<?php namespace App\Api\V1\Transformers\Campaign;
use App\Models\Order\OrderSku;
use League\Fractal\TransformerAbstract;

class OrderTicketSkuTransformer extends TransformerAbstract{

    public function transform(OrderSku $sku)
    {
        return [
            'id' => $sku['id'],
            'sku_no' => $sku['sku_no'],
            'name' => $sku['name'],
            'cover_image' => $sku['cover_image'],
            'product' => ['id' => $sku['product_id']],
            'price' => display_price($sku['price']),
            'display_price' => display_price($sku['display_price']),
            'stock' => $sku['stock'],
            'sales' => $sku['sales'],
            'attr' => json_decode($sku['attr'], true),
        ];
    }

}
