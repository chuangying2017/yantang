<?php namespace App\Repositories\Billing;

use App\Models\Billing\PreorderBilling;
use App\Repositories\NoGenerator;
use App\Repositories\Statement\StatementAbleBillingRepoContract;
use App\Repositories\Station\StationBillingRepositoryContract;
use App\Services\Billing\BillingProtocol;
use App\Services\Statement\StatementProtocol;
use Carbon\Carbon;

class PreorderBillingRepository implements BillingRepositoryContract, StationBillingRepositoryContract, StatementAbleBillingRepoContract {

    /**
     * @param $amount
     * @param $ids
     * @return PreorderBilling
     */
    public function createBilling($amount, $ids)
    {
        $billing = PreorderBilling::query()->where($ids)->first();

        //每天结算一次
        if ($billing['created_at'] > Carbon::today()) {
            return $billing;
        }

        return PreorderBilling::create([
            'billing_no' => NoGenerator::generatePreorderBillingNo(),
            'amount' => $amount,
            'preorder_id' => $ids['preorder_id'],
            'user_id' => $ids['user_id'],
            'station_id' => $ids['station_id'],
            'staff_id' => $ids['staff_id'],
            'status' => BillingProtocol::STATUS_OF_UNPAID
        ]);
    }

    public function updateAsPaid($billing_no, $pay_channel = null)
    {
        $billing = $this->getBilling($billing_no);
        if ($billing['status'] == BillingProtocol::STATUS_OF_PAID) {
            return $billing;
        }

        $billing->status = BillingProtocol::STATUS_OF_PAID;
        $billing->pay_at = Carbon::now();
        $billing->save();

        return $billing;
    }

    public function getBilling($billing_no)
    {
        if ($billing_no instanceof PreorderBilling) {
            return $billing_no;
        }

        if (strlen($billing_no) == NoGenerator::LENGTH_OF_PREORDER_BILLING_NO) {
            return PreorderBilling::query()->where('billing_no', $billing_no)->first();
        }

        return PreorderBilling::query()->find($billing_no);
    }

    public function getAllBilling($preorder_id, $status = null)
    {
        return $this->queryBillings(['preorder_id', $preorder_id], $status);
    }

    public function getBillingPaginated($preorder_id, $status = null, $per_page = BillingProtocol::BILLING_PER_PAGE)
    {
        return $this->queryBillings(['preorder_id', $preorder_id], $status, $per_page);
    }

    protected function queryBillings($owner, $status, $per_page = null, $start_time = null, $end_time = null)
    {
        $query = PreorderBilling::query()->where($owner);

        if (is_null($status)) {
            $query->where('status', $status);
        }

        if (is_null($start_time)) {
            $query->where('created_at', '>=', $start_time);
        }

        if (is_null($end_time)) {
            $query->where('created_at', '<=', $end_time);
        }

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    public function getBillingOfType($preorder_id, $pay_type)
    {
        return $this->getAllBilling($preorder_id);
    }

    public function getBillingsByStationPaginated($station_id, $status = null, $start_time = null, $end_time = null, $per_page = BillingProtocol::BILLING_PER_PAGE)
    {
        return $this->queryBillings(['station_id' => $station_id], $start_time, $per_page, $start_time, $end_time);
    }

    public function getBillingsByStaffPaginated($staff_id, $status = null, $start_time = null, $end_time = null, $per_page = BillingProtocol::BILLING_PER_PAGE)
    {
        return $this->queryBillings(['staff_id' => $staff_id], $start_time, $per_page, $start_time, $end_time);
    }

    public function getBillingWithProducts($station_id, $time_before)
    {
        return PreorderBilling::query()->with(['skus' => function ($query) {
            $query->select(['id', 'preorder_id', 'price', 'quantity', 'product_id', 'product_sku_id', 'name', 'cover_image']);
        }])->where('status', BillingProtocol::STATUS_OF_PAID)
            ->where('checkout', StatementProtocol::CHECK_STATUS_OF_PENDING)
            ->where('pay_at', '<=', $time_before)
            ->where('station_id', $station_id)
            ->get(['id', 'preorder_id', 'station_id', 'amount']);
    }

    public function updateBillingAsCheckout($billing_ids, $statement_no)
    {
        return PreorderBilling::whereIn('id', $billing_ids)->update(['checkout' => StatementProtocol::CHECK_STATUS_OF_HANDLED, 'statement_no' => $statement_no]);
    }
}
