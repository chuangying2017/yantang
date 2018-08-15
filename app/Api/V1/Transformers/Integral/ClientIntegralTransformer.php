<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\Product;
use App\Services\Home\BannerService;
use League\Fractal\TransformerAbstract;

class ClientIntegralTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {

            $data = [
                'product_id'            =>      $product['id'],
                'cover_image'           =>      $product['cover_image'],
                'integral'              =>      $product['integral'],
                'title'                 =>      $product['title']
            ];

            if ($product->relationLoaded('product_sku'))
            {
                $data['productName'] = $product->product_sku->name;
            }

            return $data;
    }
}
