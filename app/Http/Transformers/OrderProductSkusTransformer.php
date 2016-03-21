<?php namespace App\Http\Transformers;

use App\Models\OrderProduct;
use League\Fractal\TransformerAbstract;

class OrderProductSkusTransformer extends TransformerAbstract {


    public function transform(OrderProduct $sku)
    {
        return [
            'id'          => (int)$sku->id,
            'title'       => $sku->title,
            'cover_image' => $sku->cover_image,
            'quantity'    => (int)$sku->quantity,
            'attributes'  => $sku->getAttribute('attributes') ? json_decode($sku->getAttribute('attributes'), true) : null,
            'price'       => display_price($sku->pay_amount)
        ];
    }
}
