<?php

namespace App\Models\Product;

use App\Repositories\Category\CategoryProtocol;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Brand extends CategoryAbstract {

    protected $attributes = [
        'type' => CategoryProtocol::TYPE_OF_BRAND
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', CategoryProtocol::TYPE_OF_BRAND);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
