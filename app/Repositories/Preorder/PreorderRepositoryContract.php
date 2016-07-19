<?php namespace App\Repositories\Preorder;


use App\Services\Preorder\PreorderProtocol;

interface PreorderRepositoryContract {

    public function createPreorder($data);

    public function updatePreorder($order_id, $data);

    public function updatePreorderTime($order_id, $pause_time = null, $restart_time = null);

    public function updatePreorderStatus($order_id, $status);

    public function updatePreorderStatusByOrder($order_id, $status);

    public function updatePreorderAssign($order_id, $station_id = null, $staff_id = null);

    public function getPaginatedByUser($user_id, $status = null, $start_time = null, $end_time = null, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllByUser($user_id, $status = null, $start_time = null, $end_time = null);

    public function getAllPaginated($station_id = null, $order_no = null, $phone = null, $order_status = null, $start_time = null, $end_time = null);

    public function getByOrder($origin_order_id, $date = null);

    public function get($order_id, $with_detail = false);

    public function deletePreorder($order_id);

    public function getAllNotAssignOnTime($station_id = null, $per_page = null);

    public function getAllReject($station_id = null, $per_page = null);

}
