<?php
namespace App\Services\Integral\Product;

interface ProductInerface
{
    public function select();

    public function createOrUpdate(array $data = [], $id = null);

    public function delete($attach);

    public function edit();

    public function get_all_product($where=null, $orderBy = 'desc', $page='page', $pagination=20);
}