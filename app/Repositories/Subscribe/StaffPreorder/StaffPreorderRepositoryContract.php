<?php namespace App\Repositories\Subscribe\StaffPreorder;


interface StaffPreorderRepositoryContract
{

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    public function Paginated($per_page, $where = [], $order_by = 'id', $sort = 'asc');

    public function update($input, $id);

    public function updateByPreorderId($input, $preorder_id);
}