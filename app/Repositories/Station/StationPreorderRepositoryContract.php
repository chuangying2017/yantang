<?php namespace App\Repositories\Station;

use App\Services\Subscribe\PreorderProtocol;

interface StationPreorderRepositoryContract {

    public function getAllPaginatedByStation($station_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllNotDisposeByStation($station_id, $status = null);

    public function getAllPendingPaginatedByStation($station_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllDonePaginatedByStation($station_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllFeaturePaginatedByStation($station_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

}
