<?php namespace App\Services\Order\Refund\Generator;
class TempRefundOrder {

    protected $order;
    protected $skus;
    protected $discount_amount;
    protected $refund_amount;
    protected $temp_order_id;
    protected $error = null;
    protected $memo = '';

    public function __construct($order_no, $order_skus, $memo = '')
    {
        $this->setReferOrder($order_no);
        $this->setRefundSkus($order_skus);
        $this->setTempOrderId('temp_refund_' . generate_no());
        $this->memo = $memo;
    }

    public function toArray()
    {
        return [
            'order' => $this->order,
            'skus' => $this->skus,
            'refund_amount' => $this->refund_amount,
            'discount_amount' => $this->discount_amount,
            'pay_amount' => $this->getPayAmount(),
            'memo' => $this->memo,
        ];
    }


    /**
     * @param string $temp_order_id
     * @return TempRefundOrder
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

    public function setReferOrder($order)
    {
        $this->order = $order;
    }

    public function getReferOrder()
    {
        return $this->order;
    }

    public function setRefundSkus($skus)
    {
        $this->skus = $skus;
    }

    public function getRefundSkus()
    {
        return $this->skus;
    }

    public function setRefundAmount($amount)
    {
        $this->refund_amount = $amount;
    }

    public function setDiscountAmount($amount)
    {
        $this->discount_amount = $amount;
    }


    public function init($temp_order)
    {

    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getPayAmount()
    {
        $pay_amount = $this->refund_amount - $this->discount_amount;
        return $pay_amount > 0 ? $pay_amount : 0;
    }
}
