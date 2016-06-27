<?php namespace App\Repositories\Billing;

use App\Models\Billing\ChargeBilling;
use App\Repositories\NoGenerator;
use App\Services\Billing\BillingProtocol;
use Carbon\Carbon;

class ChargeBillingRepository implements BillingRepositoryContract {

    public function createBilling($amount, $user_id)
    {
        return ChargeBilling::create([
            'user_id' => $user_id,
            'billing_no' => NoGenerator::generateChargeBillingNo(),
            'amount' => $amount
        ]);
    }

	/**
     * @param $billing_no
     * @param null $pay_channel
     * @return ChargeBilling
     */
    public function updateAsPaid($billing_no, $pay_channel = null)
    {
        $billing = $this->getBilling($billing_no);

        if ($billing['status'] == BillingProtocol::STATUS_OF_PAID) {
            return $billing;
        }

        $billing->pay_channel = $pay_channel;
        $billing->status = BillingProtocol::STATUS_OF_PAID;
        $billing->pay_at = Carbon::now();
        $billing->save();

        return $billing;
    }

    public function getBilling($billing_no)
    {
        if ($billing_no instanceof ChargeBilling) {
            return $billing_no;
        }

        if (strlen($billing_no) == NoGenerator::LENGTH_OF_CHARGE_BILLING_NO) {
            return ChargeBilling::query()->where('billing_no', $billing_no)->first();
        }

        return ChargeBilling::query()->find($billing_no);
    }

    public function getAllBilling($user_id, $status = null)
    {
        if (is_null($status)) {
            return ChargeBilling::query()->where('user_id')->get();
        }
        return ChargeBilling::query()->where('user_id')->where('status', $status)->get();
    }

    public function getBillingOfType($user_id, $pay_type)
    {
        return $this->getBillingPaginated($user_id);
    }

    public function getBillingPaginated($entity_id, $status = null, $per_page = BillingProtocol::BILLING_PER_PAGE)
    {
        if (is_null($status)) {
            return ChargeBilling::query()->where('user_id')->paginate($per_page);
        }
        return ChargeBilling::query()->where('user_id')->where('status', $status)->paginate($per_page);
    }
}
