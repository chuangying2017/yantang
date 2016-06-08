<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 5/26/16
 * Time: 10:22 AM
 */

namespace App\Repositories\Promotion\Traits;


use App\Models\Promotion\PromotionDetail;

trait Detail {

    public function createDetail($promotion_id, $detail)
    {
        return PromotionDetail::create([
            'promotion_id' => $promotion_id,
            'detail' => $detail
        ]);
    }

    public function updateDetail($promotion_id, $detail)
    {
        $this->deleteDetail($promotion_id);
        return $this->createDetail($promotion_id, $detail);
//        return PromotionDetail::where('promotion_id', $promotion_id)->update(['detail' => $detail]);
    }

    public function deleteDetail($promotion_id)
    {
        return PromotionDetail::where('promotion_id', $promotion_id)->delete();
    }

    public function getDetail($promotion_id)
    {
        return PromotionDetail::find($promotion_id);
    }

}
