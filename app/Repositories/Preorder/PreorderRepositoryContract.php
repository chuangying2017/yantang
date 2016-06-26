<?php namespace App\Repositories\Preorder;


use App\Repositories\Station\StationProtocol;

interface PreorderRepositoryContract {

    public function createPreorder($data);

    public function updatePreorder($preorder_id, $start_time = null, $end_time = null, $product_skus = null);

    public function getPaginatedByUser($user_id, $status);

    public function get($preorder_id, $with_detail = false);

    public function deletePreorder($preorder_id);

}
