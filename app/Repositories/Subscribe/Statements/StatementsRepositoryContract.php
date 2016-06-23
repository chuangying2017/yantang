<?php namespace App\Repositories\Subscribe\Statements;


interface StatementsRepositoryContract
{
    /**
     * @param $per_page 0不分页
     * @param array $where where条件数组 [['field'=>'part_id_card', 'value'=>$input['id_card'], 'compare_type'=>'=']]
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function Paginated($per_page, $where = [], $order_by = 'id', $sort = 'asc');

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
    public function update($input, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function show($id);

    public function byStationId($station_id, $year, $month);

    public function destroy($id);

    public function info($per_page = null);

}