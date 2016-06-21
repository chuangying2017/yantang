<?php namespace App\Repositories\Subscribe\PreorderProductSku;


interface PreorderProductSkuRepositoryContract
{

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    public function delete($pre_product_id);
}