<?php namespace App\Repositories\Preorder;


interface PreorderRepositoryContract {

    public function createPreorder($data);

    public function updatePreorderByUser($order_id, $data);

    public function updatePreorder($order_id, $start_time = null, $end_time = null, $product_skus = null, $station_id = null);

    public function updatePreorderChargeStatus($order_id, $charge_status);

    public function updatePreorderStatus($order_id, $status);

    public function updatePreorderAssign($order_id, $station_id = null, $staff_id = null);

    public function getPaginatedByUser($user_id, $status = null);

    public function get($order_id, $with_detail = false);

    public function deletePreorder($order_id);

}
