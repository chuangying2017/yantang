<?php namespace App\Repositories\Subscribe\PreorderProductSku;

use App\Models\Subscribe\PreorderProductSku;

class EloquentPreorderProductSkuRepository implements PreorderProductSkuRepositoryContract
{

    public function moder()
    {
        return 'App\Models\Subscribe\PreorderProductSku';
    }

    public function create($input)
    {
        return PreorderProductSku::create($input);
    }

    public function delete($pre_product_id)
    {
        return PreorderProductSku::where('pre_product_id', $pre_product_id)->delete();
    }

}