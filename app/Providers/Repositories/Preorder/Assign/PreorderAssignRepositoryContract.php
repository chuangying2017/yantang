<?php namespace App\Repositories\Preorder\Assign;

interface PreorderAssignRepositoryContract {

    public function get($order_id);

    public function createAssign($order_id, $station_id);

    public function updateAssignAsConfirm($order_id);

    public function updateAssignAsReject($order_id, $memo = null);

    public function updateAssignStation($order_id, $station_id);

    public function updateAssignStaff($order_id, $staff_id);

    public function deleteAssignStaff($order_id);

    public function deleteAssign($order_id);

}
