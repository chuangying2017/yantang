<?php namespace App\Repositories\Station;

use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;

interface StationPreorderRepositoryContract extends PreorderRepositoryContract {

    public function getPreordersOfStation($station_id = null, $staff_id = null, $status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getPreordersOfStationNotConfirm($station_id);

    public function getDayPreorderWithProductsByStation($station_id, $day, $day_time = null);

    public function getDayPreordersOfStaff($staff_id = null, $day = null, $daytime = null, $per_page = null);

    public function getDayPreorderWithProductsOfStaff($staff_id, $day, $day_time = null);

    public function updatePreorderPriority($order_id, $staff_id, $preorder_priority);
}
