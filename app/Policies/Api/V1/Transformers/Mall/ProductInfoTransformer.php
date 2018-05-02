<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Product\ProductInfo;
use League\Fractal\TransformerAbstract;

class ProductInfoTransformer extends TransformerAbstract {

    public function transform(ProductInfo $info)
    {
        return [
            'product' => ['id' => $info['product_id']],
            'attr' => json_decode($info['attr'], true),
            'tags' => $info['tags'],
            'detail' => $info['detail']
        ];
    }
}
