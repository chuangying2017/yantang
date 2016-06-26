<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Product\ProductMeta;
use League\Fractal\TransformerAbstract;

class ProductMetaTransformer extends TransformerAbstract{

    /**
     * ProductMetaTransformer constructor.
     */
    public function transform(ProductMeta $meta)
    {
        return [
            'product' => ['id' => $meta['product_id']],
            'favs' => $meta['favs'],
            'sales' => $meta['sales'],
            'stock' => $meta['stock']
        ];
    }
}
