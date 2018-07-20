<?php
namespace App\Services\Integral\Product;

interface ProductInerface
{
    public function select();

    public function createOrUpdate(array $data = [], $id = null);

    public function delete($attach);

    public function edit($id,$status);

    public function get_all_product($where=null,$page = 1,$sort='updated_at', $orderBy = 'desc', $pagination = 20);
}