<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 6/5/16
 * Time: 6:12 PM
 */

namespace App\Services\Promotion\Data\Traits;


trait PromotionBenefitData {

    protected $discount_amount = 0;
    protected $discount_express_fee = 0;
    protected $amount = 0;
    protected $express_fee = 0;

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setDiscountAmount($discount_amount)
    {
        $this->discount_amount = $discount_amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getExpressFee()
    {
        return $this->express_fee;
    }

    public function setExpressFee($express_fee = null)
    {
        if (!$express_fee) {
            #todo 获取运费接口
            $this->express_fee = 0;
        }
        $this->$express_fee = $express_fee;
    }

    public function setDiscountExpressFee($discount_express_fee)
    {
        $this->discount_express_fee = $discount_express_fee;
    }

    /**
     * 添加优惠
     */

    public function addDiscountAmount($amount)
    {
        $this->discount_amount = $this->discount_amount + $amount;
        if ($this->discount_amount > $this->amount) {
            $this->discount_amount = $this->amount;
        }
        return $this;
    }

    public function subDiscountAmount($amount)
    {
        $this->discount_amount = $this->discount_amount - $amount;
        if ($this->discount_amount < 0) {
            $this->discount_amount = 0;
        }
    }

    public function addDiscountExpressFee($amount)
    {
        $this->discount_express_fee = $this->discount_express_fee + $amount;
        if ($this->discount_express_fee > $this->express_fee) {
            $this->discount_express_fee = $this->express_fee;
        }
        return $this;
    }

    public function subDiscountExpressFee($amount)
    {
        $this->discount_express_fee = $this->discount_express_fee - $amount;
        if ($this->discount_express_fee < 0) {
            $this->discount_express_fee = 0;
        }
    }



}
