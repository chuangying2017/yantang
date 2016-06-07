<?php namespace App\Repositories\Billing;

use App\Models\Billing\OrderBilling;
use App\Services\Order\OrderProtocol;

class OrderBillingRepository implements BillingRepositoryContract {

    protected $pay_type = null;

    public function createBilling($amount, $order_id)
    {
        return OrderBilling::create([
            'order_id' => $order_id,
            'amount' => $amount,
            'pay_type' => $this->getPayType(),
            'status' => OrderProtocol::PAID_STATUS_OF_UNPAID
        ]);
    }

    public function updateAsPaid($billing_no, $pay_channel)
    {
        $billing = $this->getBilling($billing_no);
        $billing->pay_channel = $pay_channel;
        $billing->status = OrderProtocol::PAID_STATUS_OF_PAID;
        $billing->save();
        return $billing;
    }

    public function getBilling($billing_no)
    {
        return OrderBilling::where('billing_no', $billing_no)->first();
    }

    public function getBillingOfType($order_id, $pay_type)
    {
        return OrderBilling::order($order_id)->where('pay_type', $pay_type)->first();
    }

    public function getAllBilling($order_id, $status = null)
    {
        if (is_null($status)) {
            return OrderBilling::order($order_id)->get();
        }
        return OrderBilling::order($order_id)->where('status', $status)->get();
    }

    public function setType($pay_type)
    {
        $this->pay_type = $pay_type;
    }

    public function getPayType()
    {
        if (is_null($this->pay_type)) {
            throw new \Exception('订单账单类型错误');
        }
        return $this->pay_type;
    }
}
