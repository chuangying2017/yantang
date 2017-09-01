<?php namespace App\Repositories\Preorder;


use App\Models\Subscribe\Preorder;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

interface PreorderRepositoryContract {

    public function createPreorder($data);

    public function updatePreorder($order_id, $data);

    public function updatePreorderTime($order_id, $pause_time = null, $restart_time = null);

    public function updatePreorderStatus($order_id, $status);

    public function updatePreorderAsDeliver($order_id);

    public function updatePreorderStatusByOrder($order_id, $status);

    public function updatePreorderAssign($order_id, $station_id = null, $staff_id = null);

    public function getPaginatedByUser($user_id, $status = null, $start_time = null, $end_time = null, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllByUser($user_id, $status = null, $start_time = null, $end_time = null);

    /**
     * @param null $station_id
     * @param null $order_no
     * @param null $pay_order_no
     * @param null $phone
     * @param null $order_status
     * @param null $start_time
     * @param null $end_time
     * @param string $time_name
     * @return mixed
     */
    public function getAllPaginated($station_id = null, $order_no = null, $pay_order_no = null, $phone = null, $order_status = null, $start_time = null, $end_time = null, $time_name = 'created_at', $invoice = null, $residence_id = null);

    /**
     * @param null $station_id
     * @param null $order_no
     * @param null $pay_order_no
     * @param null $phone
     * @param null $order_status
     * @param null $start_time
     * @param null $end_time
     * @param string $time_name
     * @return Collection
     */
    public function getAll($station_id = null, $order_no = null, $pay_order_no = null, $phone = null, $order_status = null, $start_time = null, $end_time = null, $time_name = 'created_at', $invoice = null, $residence_id = null);

    public function getByOrder($origin_order_id, $date = null);

	/**
     * @param $order_id
     * @param bool $with_detail
     * @return Preorder
     */
    public function get($order_id, $with_detail = false);

    public function deletePreorder($order_id);

    public function getAllNotAssignOnTime($station_id = null, $per_page = null);

    public function getAllReject($station_id = null, $per_page = null);
    
    public function getAllEnding($day = 3, $per_page = null);

}
