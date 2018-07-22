<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\Product;
use League\Fractal\TransformerAbstract;

class ClientDetailTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
            $product_sku = $product->product_sku->first();
            $data = [
                'id'                        =>      $product['id'],
                'title'                     =>      $product['title'],
                'price'                     =>      $product['price'],
                'integral'                  =>      $product['integral'],
                'status'                    =>      $product['status'],
                'detail'                    =>      $product['detail'],
                'digest'                    =>      $product['digest'],
                'advertising'               =>      $product['advertising'],
                'remainder'                 =>      $product_sku['remainder'],//剩余量
                'postage'                   =>      array_get($product_sku,'postage',0),
                'name'                      =>      $product_sku['name'],
            ];

            if ($product->relationLoaded('specification')) {
                $data['specification']  =   $product->specification()->get(['id','type','describe']);
            }

            if ($product->relationLoaded('images')) {
                $data['image_ides'] = array_map(function ($media_id){
                    return config('filesystems.disks.qiniu.domains.custom') . $media_id;
                },$product->images->pluck('media_id'));
            }

            return $data;
    }
}
