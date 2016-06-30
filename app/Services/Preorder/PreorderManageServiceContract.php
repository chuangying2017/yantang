<?php namespace App\Services\Preorder;
interface PreorderManageServiceContract {

    public function init($preorder_id, $product_skus, $start_time, $end_time = null);

    public function change($preorder_id, $product_skus = null, $start_time = null, $end_time = null);

    public function pause($preorder_id, $pause_time, $restart_time = null);

    public function charged($user_id);

    public function needCharge($user_id);

    public function stationDailyInfo($station_id, $day = null, $daytime = null);

    public function staffDailyInfo($staff_id, $day = null, $daytime = null);


}
