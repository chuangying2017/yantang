<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\Product;
use League\Fractal\TransformerAbstract;

class ClientIntegralTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
            $productSku = $product->product_sku->first();

            $data = [
                'cover_image'           =>      $product['cover_image'],
                'productName'           =>      $productSku['name'],
                'integral'              =>      $product['integral'],
                'title'                 =>      $product['title']
            ];

            return $data;
    }
}
