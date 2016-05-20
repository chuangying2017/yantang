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
    
}