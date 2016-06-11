<?php namespace App\Services\Order\Generator;

class TempOrder {

    protected $temp_order_id;
    protected $address;
    protected $total_amount;
    protected $sku_amount;
    protected $express_fee;
    protected $user;
    protected $skus;
    protected $promotion;
    protected $error;
    protected $product_amount = 0;
    protected $discount_amount;

    public function __construct($user_id, $skus, $address = null)
    {
        $this->setUser($user_id);
        $this->setSkus($skus);
        $this->setAddress($address);
        $this->setTempOrderId('temp_' . generate_no());
    }

    public function get()
    {
        return [
            'temp_order_id' => $this->temp_order_id,
            'user' => $this->user,
            'skus' => $this->skus,
            'address' => $this->address,
            'total_amount' => $this->total_amount,
            'product_amount' => $this->product_amount,
            'discount_amount' => $this->discount_amount,
            'express_fee' => $this->express_fee,
            'promotion' => $this->promotion
        ];
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * @param mixed $total_amount
     */
    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }

    /**
     * @return mixed
     */
    public function getExpressFee()
    {
        return $this->express_fee;
    }

    /**
     * @param mixed $express_fee
     */
    public function setExpressFee($express_fee)
    {
        $this->express_fee = $express_fee;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getSkus()
    {
        return $this->skus;
    }

    /**
     * @param mixed $skus
     */
    public function setSkus($skus)
    {
        $this->skus = $skus;
        $product_amount = 0;
        foreach($skus as $sku) {
            $product_amount = bcadd($sku['price'], $product_amount, 0);
        }
        $this->setProductAmount($product_amount);
    }

    /**
     * @return mixed
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @param mixed $promotion
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @return mixed
     */
    public function getSkuAmount()
    {
        return $this->sku_amount;
    }

    /**
     * @param mixed $sku_amount
     */
    public function setSkuAmount($sku_amount)
    {
        $this->sku_amount = $sku_amount;
    }

    /**
     * @param string $temp_order_id
     * @return TempOrder
     */
    public function setTempOrderId($temp_order_id)
    {
        $this->temp_order_id = $temp_order_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempOrderId()
    {
        return $this->temp_order_id;
    }

    /**
     * @param mixed $error
     * @return TempOrder
     */
    public function setError($error, $over_write = false)
    {
        if ($over_write) {
            $this->error = [$error];
        } else {
            $this->error[] = $error;
        }
    }

    /**
     * @param mixed $product_amount
     */
    public function setProductAmount($product_amount)
    {
        $this->product_amount = $product_amount;
    }

    /**
     * @param mixed $discount_amount
     */
    public function setDiscountAmount($discount_amount)
    {
        $this->discount_amount = $discount_amount;
    }

}
