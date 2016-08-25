<?php namespace App\Api\V1\Transformers\Mall;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Client\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['sku'];

    public function transform(Cart $cart)
    {
        $this->setInclude($cart);

        return [
            'id' => $cart['id'],
            'quantity' => $cart['quantity'],
            'product_sku_id' => $cart['product_sku_id']
        ];
    }

    public function includeSku(Cart $cart)
    {
        return $this->item($cart->sku, new ProductSkuTransformer(), true);
    }
}
