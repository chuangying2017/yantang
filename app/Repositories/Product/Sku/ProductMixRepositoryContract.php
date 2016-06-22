<?php namespace App\Repositories\Product\Sku;
interface ProductMixRepositoryContract {

    public function getAllMixAbleProductSku();

    public function getMixSkus($mix_sku_id);



}
