<?php namespace App\Repositories\Billing;

use App\Events\Order\MainBillingIsPaid;
use App\Models\Billing\OrderBilling;
use App\Repositories\NoGenerator;
use App\Services\Billing\BillingProtocol;

class OrderBillingRepository implements BillingRepositoryContract {

    protected $pay_type = null;

    public function createBilling($amount, $order_id)
    {
        return OrderBilling::create([
            'billing_no' => NoGenerator::generateOrderBillingNo(),
            'order_id' => $order_id,
            'amount' => $amount,
            'pay_type' => $this->getPayType(),
            'status' => BillingProtocol::STATUS_OF_UNPAID
        ]);
    }

    public function updateAsPaid($billing_no, $pay_channel = null)
    {
        $billing = $this->getBilling($billing_no);

        if ($billing['status'] == BillingProtocol::STATUS_OF_PAID) {
            return $billing;
        }

        if(!is_null($pay_channel)) {
            $billing->pay_channel = $pay_channel;
        }
        $billing->status = BillingProtocol::STATUS_OF_PAID;
        $billing->save();

        event(new MainBillingIsPaid($billing));

        return $billing;
    }

	/**
     * @param $billing_no
     * @return OrderBilling
     */
    public function getBilling($billing_no)
    {
        if ($billing_no instanceof OrderBilling) {
            return $billing_no;
        }

        if (strlen($billing_no) == NoGenerator::LENGTH_OF_ORDER_BILLING_NO) {
            return OrderBilling::query()->where('billing_no', $billing_no)->first();
        }

        return OrderBilling::query()->find($billing_no);
    }

    public function getBillingOfType($order_id, $pay_type = BillingProtocol::BILLING_TYPE_OF_MONEY)
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
        return $this;
    }

    public function getPayType()
    {
        if (is_null($this->pay_type)) {
            throw new \Exception('订单账单类型错误');
        }
        return $this->pay_type;
    }

    public function getBillingPaginated($order_id, $status = null, $per_page = BillingProtocol::BILLING_PER_PAGE)
    {
        if (is_null($status)) {
            return OrderBilling::order($order_id)->paginate($per_page);
        }
        return OrderBilling::order($order_id)->where('status', $status)->paginate($per_page);
    }
}
