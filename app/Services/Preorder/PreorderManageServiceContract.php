<?php namespace App\Services\Preorder;
interface PreorderManageServiceContract {

    public function restart($order_id, $start_time);

    public function pause($order_id, $pause_time, $restart_time = null);

    public function stationDailyInfo($station_id, $day = null, $daytime = null);

    public function staffDailyInfo($staff_id, $day = null, $daytime = null);


}
