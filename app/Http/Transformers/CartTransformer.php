<?php namespace App\Http\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract {


    public function transform(Cart $cart)
    {
        if (isset($cart->product_sku)) {
            $this->setDefaultIncludes(['sku']);
        }

        return [
            'id'             => (int)$cart->id,
            'product_sku_id' => (int)$cart->product_sku_id,
            'quantity'       => (int)$cart->quantity,
        ];
    }

    public function includeSku(Cart $cart)
    {
        $sku = $cart->product_sku;

        return $this->item($sku, new ProductSkuTransformer());
    }

}
