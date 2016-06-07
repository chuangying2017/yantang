<?php namespace App\Http\Transformers;

use App\Models\Order\OrderSku;
use League\Fractal\TransformerAbstract;

class OrderProductSkusTransformer extends TransformerAbstract {


    public function transform(OrderSku $sku)
    {
        return [
            'id'          => (int)$sku->id,
            'product_id'  => (int)$sku->product_id,
            'title'       => $sku->title,
            'cover_image' => $sku->cover_image,
            'quantity'    => (int)$sku->quantity,
            'attributes'  => $sku->getAttribute('attributes') ? json_decode($sku->getAttribute('attributes'), true) : null,
            'price'       => display_price($sku->pay_amount)
        ];
    }
}
