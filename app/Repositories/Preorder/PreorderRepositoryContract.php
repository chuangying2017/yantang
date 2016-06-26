<?php namespace App\Repositories\Preorder;


interface PreorderRepositoryContract
{

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param int $user_id
     * @return mixed
     */
    public function byUserId($user_id);

    public function byStationId($station_id, $status, $pre_page);

    public function update($input, $preorder_id);

    public function byId($preorder_id, $with = []);

    public function searchInfo($per_page, $order_no, $begin_time, $end_time, $phone, $status);

    public function getOrderStaffId($preorder_id);

}
