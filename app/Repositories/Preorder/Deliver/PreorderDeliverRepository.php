<?php namespace App\Repositories\Preorder\Deliver;

use App\Models\Subscribe\PreorderDeliver;
use App\Repositories\Statement\StatementAbleBillingRepoContract;
use App\Services\Preorder\PreorderProtocol;
use App\Services\Statement\StatementProtocol;

class PreorderDeliverRepository implements PreorderDeliverRepositoryContract, StatementAbleBillingRepoContract {

    public function createDeliver($data)
    {
        return PreorderDeliver::create(array_only($data, [
            'user_id',
            'station_id',
            'staff_id',
            'preorder_id',
            'deliver_at'
        ]));
    }

    public function updateAsSuccess($deliver_id)
    {
        $deliver = $this->get($deliver_id);
        $deliver->status = PreorderProtocol::PREORDER_DELIVER_STATUS_OF_OK;
        $deliver->save();

        return $deliver;
    }

    public function get($deliver_id)
    {
        if ($deliver_id instanceof PreorderDeliver) {
            return $deliver_id;
        }
        return PreorderDeliver::query()->findOrFail($deliver_id);
    }

    public function updateAsFail($deliver_id)
    {
        $deliver = $this->get($deliver_id);
        $deliver->status = PreorderProtocol::PREORDER_DELIVER_STATUS_OF_ERROR;
        $deliver->save();

        return $deliver;
    }

    public function getBillingWithProducts($station_id, $time_before)
    {
        return PreorderDeliver::query()->with(['skus' => function ($query) {
            $query->select(['id', 'preorder_id', 'product_id', 'product_sku_id', 'name', 'cover_image']);
        }])->where('status', PreorderProtocol::PREORDER_DELIVER_STATUS_OF_OK)
            ->where('checkout', StatementProtocol::CHECK_STATUS_OF_PENDING)
            ->where('deliver_at', '<=', $time_before)
            ->where('station_id', $station_id)
            ->get(['id', 'preorder_id', 'station_id']);
    }

    public function updateBillingAsCheckout($deliver_ids, $statement_no)
    {
        return PreorderDeliver::whereIn('id', $deliver_ids)->update(['checkout' => StatementProtocol::CHECK_STATUS_OF_HANDLED, 'statement_no' => $statement_no]);
    }

    public function getRecentDeliver($preorder_id, $deliver_at)
    {
        $deliver = PreorderDeliver::query()->where('preorder_id', $preorder_id)->orderBy('created_at', 'desc')->first();

        if ($deliver && $deliver['deliver_at'] >= $deliver_at) {
            return false;
        }

        return $deliver;
    }
}
