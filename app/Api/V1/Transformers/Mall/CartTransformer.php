<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Client\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract {

    protected $availableIncludes = ['sku'];

    public function transform(Cart $cart)
    {
        $this->setDefaultIncludes('sku');

        return [
            'id' => $cart['id'],
            'quantity' => $cart['quantity'],
            'product_sku_id' => $cart['product_sku_id']
        ];
    }

    public function includeSku(Cart $cart)
    {
        return $this->collection($cart->sku, new ProductSkuTransformer(), true);
    }
}
