<?php

namespace App\Models\Client;

use App\Models\Client\Traits\BindUser;
use App\Models\Product\ProductSku;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
    use BindUser;

    protected $table = 'carts';

    protected $guarded = ['id'];

    public function sku()
    {
        return $this->hasOne(ProductSku::class, 'id', 'product_sku_id');
    }

}
