<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 5/26/16
 * Time: 10:22 AM
 */

namespace App\Repositories\Promotion\Traits;


use App\Models\Promotion\PromotionSku;

trait Skus {

    public function createSku($promotion_id, $product_sku_id)
    {
        return PromotionSku::create([
            'promotion_id' => $promotion_id,
            'product_sku_id' => $product_sku_id
        ]);
    }

    public function updateSku($promotion_id, $product_sku_id)
    {
        $this->deleteSku($promotion_id);
        return $this->createSku($promotion_id, $product_sku_id);
    }

    public function deleteSku($promotion_id)
    {
        return PromotionSku::where('promotion_id', $promotion_id)->delete();
    }

    public function getSku($promotion_id)
    {
        return PromotionSku::find($promotion_id);
    }

}
