<?php namespace App\Services\Preorder;

interface PreorderAssignServiceContract {

    public function confirm($station_id, $preorder_id);

    public function reject($station_id, $preorder_id, $memo);

}
