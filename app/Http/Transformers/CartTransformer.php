<?php namespace App\Http\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract {


    public function transform(Cart $cart)
    {
        $detail = [];
        if (isset($cart->product_sku)) {
            $productSku = $cart->product_sku;
            $detail = [
                'product_id'  => (int)$productSku['product_id'],
                'sku_no'      => $productSku['sku_no'],
                'stock'       => (int)$productSku['stock'],
                'sales'       => (int)$productSku['sales'],
                'price'       => (int)display_price($productSku['price']),
                'merchant_id' => (int)$productSku['merchant_id'],
                'title'       => $productSku['title'],
                'category_id' => (int)$productSku['category_id'],
                'cover_image' => $productSku['cover_image'],
                'attributes'  => $productSku['attributes'] ? json_decode('[' . $productSku['attributes'] . ']', true) : null
            ];
        }

        return array_merge([
            'id'             => (int)$cart->id,
            'product_sku_id' => (int)$cart->product_sku_id,
            'quantity'       => (int)$cart->quantity,
            'status'         => (int)$cart->status,
        ], $detail);
    }


}
