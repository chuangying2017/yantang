<?php namespace App\Services\Billing;

use App\Models\Subscribe\ChargeBilling;
use Auth;

class PreorderBilling implements BillingContract
{
    private $id;
    private $charge_billing;

    /**
     * @return mixed
     **/
    public function create($amount)
    {
        $input = [
            'user_id' => access()->id(),
//            'user_id' => 2,
            'billing_no' => uniqid('bill'),
            'amount' => $amount * 10,
        ];
        $charge_billing = ChargeBilling::create($input);
        $this->charge_billing = $charge_billing;
        $this->id = $charge_billing->id;
        return $charge_billing;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getOrderNo()
    {
        return empty($this->charge_billing) ? ChargeBilling::find($this->id)->billing_no : $this->charge_billing->billing_no;
    }

    public function isPaid()
    {
        $status = empty($this->charge_billing) ? ChargeBilling::find($this->id)->status : $this->charge_billing->status;
        return $status == 1 ? true : false;
    }

    public function getAmount()
    {
        return empty($this->charge_billing) ? ChargeBilling::find($this->id)->amount : $this->charge_billing->amount;
    }

    public function getType()
    {
        return BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING;
    }

    public function getPayer()
    {
        return empty($this->charge_billing) ? ChargeBilling::find($this->id)->user_id : $this->charge_billing->user_id;
    }

    public function setPaid($pay_type, $pay_channel)
    {
        //status 充值状态0,未支付,1已支付
        return ChargeBilling::find($this->id)->update(['pay_type' => $pay_type, 'pay_channel' => $pay_channel, 'status' => 1]);
    }

    public function setID($billing_id)
    {
        $this->id = $billing_id;
    }

}
