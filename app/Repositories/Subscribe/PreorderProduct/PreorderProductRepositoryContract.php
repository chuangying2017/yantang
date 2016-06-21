<?php namespace App\Repositories\Subscribe\PreorderProduct;


interface PreorderProductRepositoryContract
{

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param int $preorder_id
     * @return mixed
     */
    public function byWhere($preorder_id);

    /**
     * @param int $id
     * @return mixed
     */
    public function byId($id);

    public function update($input, $id);

    public function byPreorderId($preorder_id, $weekday);
}