<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\PreorderSku;

class PreorderSkuTransformer extends TransformerAbstract {

    public function transform(PreorderSku $sku)
    {
        $data = [
            'id' => $sku->id,
            'preorder' => ['id' => $sku->preorder_id],
//            'skus' => $this->setProductSku($sku->skus),
            'weekday' => $sku->weeday,
            'daytime' => $sku->daytime,
            'created_at' => $sku->created_at,
            'updated_at' => $sku->updated_at,
        ];

        return $data;
    }

    protected function setProductSku($skus)
    {
        $data = [];
        foreach ($skus as $sku) {
            $data[] = [
                'id' => $sku->product_sku_id,
                'name' => $sku->name,
                'cover_image' => $sku->cover_image,
                'quantity' => $sku->quantity,
                'price' => $sku->price,
                'total_amount' => $sku->total_amount,
            ];
        }

        return $data;
    }
}
