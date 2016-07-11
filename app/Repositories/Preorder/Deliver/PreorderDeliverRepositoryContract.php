<?php namespace App\Repositories\Preorder\Deliver;
use App\Models\Subscribe\PreorderDeliver;

interface PreorderDeliverRepositoryContract {

    public function getRecentDeliver($preorder_id, $deliver_at);

	/**
     * @param $data
     * @return PreorderDeliver
     */
    public function createDeliver($data);

	/**
     * @param $deliver_id
     * @return PreorderDeliver
     */
    public function updateAsSuccess($deliver_id);

    /**
     * @param $deliver_id
     * @return PreorderDeliver
     */
    public function updateAsFail($deliver_id);

}
