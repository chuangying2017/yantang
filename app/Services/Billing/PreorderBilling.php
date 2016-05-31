<?php namespace App\Services\Billing;

use App\Models\Subscribe\ChargeBilling;
use Auth;

class PreorderBilling implements BillingContract
{
    private $id;


    /**
     * @return mixed
     **/
    public function create($amount)
    {
        $input = [
            'user_id' => Auth::user()->id(),
//            'user_id' => 2,
            'billing_no' => uniqid('bill_'),
            'amount' => $amount * 10,
        ];
        $charge_billing = ChargeBilling::create($input);
        $this->id = $charge_billing->id;
        return $charge_billing;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getOrderNo()
    {
        return ChargeBilling::find($this->id)->billing_no;
    }

    public function isPaid()
    {
        $status = ChargeBilling::find($this->id)->status;
        return $status == 1 ? true : false;
    }

    public function getAmount()
    {
        return ChargeBilling::find($this->id)->amount;
    }

    public function getType()
    {
        return BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING;
    }

    public function getPayer()
    {
        return ChargeBilling::find($this->id)->user_id;
    }

    public function setPaid($pay_type, $pay_channel)
    {
        //status 充值状态0,未支付,1已支付
        return ChargeBilling::find($this->id)->update(['pay_type' => $pay_type, 'pay_channel' => $pay_channel, 'status' => 1]);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

}
