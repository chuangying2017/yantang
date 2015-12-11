<?php namespace App\Http\Transformers;

use App\Models\OrderProductView;
use League\Fractal\TransformerAbstract;

class OrderProductSkusTransformer extends TransformerAbstract {


    public function transform(OrderProductView $sku)
    {
        return [
            'id'         => $sku->id,
            'title'      => $sku->title,
            'image'      => $sku->cover_image,
            'quantity'   => $sku->quantity,
            'attributes' => $sku->attributes ? json_decode('[' . $sku->attributes . ']', true) : null,
            'price'      => $sku->pay_amount
        ];
    }
}
