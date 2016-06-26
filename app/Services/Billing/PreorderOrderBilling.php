<?php namespace App\Services\Billing;

use App\Models\Subscribe\PreorderOrderBillings;
use App\Services\Subscribe\SubscribeProtocol;

class PreorderOrderBilling implements BillingContract
{
    private $id;
    private $preorder_order_billing;

    /**
     * @return mixed
     **/
    public function create($amount, $status)
    {
        $input = [
            'billing_no' => uniqid(SubscribeProtocol::PRE_ORDER_BILLINGS_NO_PREFIX),
            'amount' => $amount,
            'status' => $status,
        ];
        $preorder_order_billing = PreorderOrderBillings::create($input);
        $this->preorder_order_billing = $preorder_order_billing;
        $this->id = $preorder_order_billing->id;
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getOrderNo()
    {
        return empty($this->preorder_order_billing) ? PreorderOrderBillings::find($this->id)->billing_no : $this->preorder_order_billing->billing_no;
    }

    public function isPaid()
    {
        $status = empty($this->preorder_order_billing) ? PreorderOrderBillings::find($this->id)->status : $this->preorder_order_billing->status;
        return $status == SubscribeProtocol::PRE_ORDER_BILLINGS_STATUS_OF_PAID ? true : false;
    }

    public function getAmount()
    {
        return empty($this->preorder_order_billing) ? PreorderOrderBillings::find($this->id)->amount : $this->preorder_order_billing->amount;
    }

    public function getType()
    {
        return BillingProtocol::BILLING_TYPE_OF_PREORDER_ORDER_BILLING;
    }

    public function getPayer()
    {
        return empty($this->preorder_order_billing) ? PreorderOrderBillings::find($this->id)->user_id : $this->preorder_order_billing->user_id;
    }

    public function setPaid()
    {
        return PreorderOrderBillings::find($this->id)->update(['status' => SubscribeProtocol::PRE_ORDER_BILLINGS_STATUS_OF_PAID]);
    }

    public function setID($billing_id)
    {
        $this->id = $billing_id;
        return $this;
    }

}
