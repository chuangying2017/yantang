<?php namespace App\Repositories\Subscribe\PreorderOrder;


interface PreorderOrderRepositoryContract
{
    public function create($input);

    public function Paginated($per_page, $where = [], $order_by = 'id', $sort = 'asc');

    public function update($input, $id);
}