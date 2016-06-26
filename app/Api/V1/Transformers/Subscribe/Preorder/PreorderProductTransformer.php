<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderProduct;

class PreorderProductTransformer extends TransformerAbstract
{

    public function transform(PreorderProduct $preorder_product)
    {

        $this->setDefaultIncludes(['sku']);

        $data = [
            'id' => $preorder_product->id,
            'preorder_id' => $preorder_product->preorder_id,
            'weekday' => $preorder_product->weekday,
            'daytime' => $preorder_product->daytime,
            'created_at' => $preorder_product->created_at,
            'updated_at' => $preorder_product->updated_at,
        ];

        return $data;
    }


    public function includeSku(PreorderProduct $preorder_product)
    {
        $sku = $preorder_product->sku;
        return $this->collection($sku, new PreorderProductSkuTransformer());
    }

}
