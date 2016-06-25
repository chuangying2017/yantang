<?php namespace App\Repositories\Subscribe\StaffWeekly;


interface StaffWeeklyRepositoryContract
{
    public function create($input);

    public function Paginated($per_page, $where = [], $order_by = 'id', $sort = 'asc');

    public function update($input, $id);

    public function updateByOther($week_of_year, $preorder_id, $staff_id, $data);

    public function pause($week_of_year, $preorder_id, $staff_id, $day_of_week);

    public function byStaffId($staff_id, $week_of_year, $column);

    public function getOneDayDelivery($week_of_year, $week_name, $tomorrow_column);

    public function getUserOneDayDelivery($preorder_id, $week_of_year, $week_name);
}