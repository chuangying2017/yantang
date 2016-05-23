<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderProductSku;

class PreorderProductSkuTransformer extends TransformerAbstract
{

    public function transform(PreorderProductSku $preorderProductSku)
    {
        $data = [
            'pre_product_id' => $preorderProductSku->pre_product_id,
            'sku_id' => $preorderProductSku->sku_id,
            'count' => $preorderProductSku->count,
            'sku_name' => $preorderProductSku->sku_name,
        ];
        
        return $data;
    }

}
