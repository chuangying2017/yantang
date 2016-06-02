<?php namespace App\Services\Promotion\Data\Traits;
trait PromotionExpressFeeData {

    /**
     * @param mixed $express_fee
     */
    public function setExpressFee($express_fee = null)
    {
        if (!$express_fee) {
            #todo 获取运费接口
            $this->express_fee = 0;
        }
        $this->$express_fee = $express_fee;
    }

    /**
     * @param int $discount_express_fee
     */
    public function setDiscountExpressFee($discount_express_fee)
    {
        $this->discount_express_fee = $discount_express_fee;
    }

}
