<?php namespace App\Repositories\Station\Staff;


interface StaffRepositoryContract
{
    public function createStaff($station_id, $staff_data);

    public function updateStaff($staff_id, $staff_data);

    public function bindUser($staff_id, $user_id);

    public function unbindUser($staff_id, $user_id);

    public function updateAsActive($staff_ids);

    public function updateAsUnActive($staff_ids);

    public function deleteStaff($staff_id);

    public function getStaff($staff_id, $with_user = false);

    public function getBindToken($staff_id);

    public function getStaffByUser($user_id, $throw_error = false);

    public function getStaffIdByUser($user_id);

    public function getAll($station_id, $status = null, $with_trash = false);

    public function getAllActive($station_id);

    public function getAllUnActive($station_id);

}
