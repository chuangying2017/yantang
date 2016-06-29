<?php namespace App\Repositories\Preorder;


use App\Services\Preorder\PreorderProtocol;

interface PreorderRepositoryContract {

    public function createPreorder($data);

    public function updatePreorder($order_id, $data);

    public function updatePreorderChargeStatus($order_id, $charge_status);

    public function updatePreorderStatus($order_id, $status);

    public function updatePreorderAssign($order_id, $station_id = null, $staff_id = null);

    public function getPaginatedByUser($user_id, $status = null, $start_time = null, $end_time = null, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllByUser($user_id, $status = null, $start_time = null, $end_time = null);

    public function get($order_id, $with_detail = false);

    public function deletePreorder($order_id);

}
