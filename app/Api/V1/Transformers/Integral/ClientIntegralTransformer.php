<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\Product;
use App\Services\Home\BannerService;
use League\Fractal\TransformerAbstract;

class ClientIntegralTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
            $productSku = $product->product_sku->first();

            $data = [
                'product_id'            =>      $product['id'],
                'cover_image'           =>      $product['cover_image'],
                'productName'           =>      $productSku['name'],
                'integral'              =>      $product['integral'],
                'title'                 =>      $product['title']
            ];

            return $data;
    }
}
