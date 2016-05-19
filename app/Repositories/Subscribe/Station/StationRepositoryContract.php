<?php namespace App\Repositories\Subscribe\Station;


interface StationRepositoryContract
{
    /**
     * @param int $user_id
     * @return mixed
     */
    public function getByUserId($user_id);

    /**
     * @param int $id
     * @return mixed
     */
    public function getInfoById($id);

    /**
     * @param int $station_id
     * @param int $user_id
     * @return mixed
     */
    public function bindStation($station_id, $user_id);

    /**
     * @param $per_page 0不分页
     * @param array $where where条件数组 [['field'=>'part_id_card', 'value'=>$input['id_card'], 'compare_type'=>'=']]
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function Paginated($per_page, $where, $order_by = 'id', $sort = 'asc');

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param $id
     * @return mixed
     */
    public function show($id);

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id);
}