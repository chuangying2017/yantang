<?php

namespace App\Models;

use App\Models\Product\ProductSku;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {

    protected $table = 'carts';

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', access()->id());
        });
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = access()->id();
    }

    public function sku()
    {
        return $this->hasOne(ProductSku::class, 'product_sku_id', 'id');
    }

}
