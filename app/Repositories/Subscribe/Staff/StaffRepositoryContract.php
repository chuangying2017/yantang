<?php namespace App\Repositories\Subscribe\Staff;


interface StaffRepositoryContract
{
    /**
     * @param $per_page
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getStaffPaginated($station_id, $per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param $id
     * @return mixed
     */
    public function show($id);

    /**
     * @param $id
     * @param $input
     * @param $station_id
     * @return mixed
     */
    public function update($id, $input, $station_id);

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id);


    public function byUserId($user_id, $with_orders = false);

    public function bindStaff($staff_id, $user_id);

}