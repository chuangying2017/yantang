<?php namespace App\Repositories\Station\Staff;

use App\Services\Subscribe\PreorderProtocol;

interface StaffPreorderRepositoryContract {

    public function getAllPaginatedByStaff($staff_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllNotDisposeByStaff($staff_id, $status = null);

    public function getAllPendingPaginatedByStaff($staff_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllDonePaginatedByStaff($staff_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getAllFeaturePaginatedByStaff($staff_id, $per_page = PreorderProtocol::PREORDER_PER_PAGE);

}
