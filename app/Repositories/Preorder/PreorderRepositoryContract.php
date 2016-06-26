<?php namespace App\Repositories\Preorder;


use App\Repositories\Station\StationProtocol;

interface PreorderRepositoryContract {

    public function createPreorder($data);

    public function updatePreorder($data);

    public function getPaginatedByUser($user_id, $status);

    public function getPaginatedByStation($station_id, $status, $per_page = StationProtocol::STATION_PER_PAGE);

    public function getPaginatedByStaff($staff_id, $status, $per_page = StationProtocol::STATION_PER_PAGE);

    public function get($preorder_id, $with_detail);

    public function deletePreorder($preorder_id);

}
