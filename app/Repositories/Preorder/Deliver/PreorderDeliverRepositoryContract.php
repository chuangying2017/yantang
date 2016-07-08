<?php namespace App\Repositories\Preorder\Deliver;
use App\Models\Subscribe\PreorderDeliver;

interface PreorderDeliverRepositoryContract {

	/**
     * @param $data
     * @return PreorderDeliver
     */
    public function createDeliver($data);

    public function updateAsSuccess($deliver_id);

    public function updateAsFail($deliver_id);

}
