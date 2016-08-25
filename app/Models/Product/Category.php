<?php

namespace App\Models\Product;

use App\Repositories\Category\CategoryProtocol;
use Illuminate\Database\Eloquent\Builder;

class Category extends CategoryAbstract {

    protected $attributes = [
        'type' => CategoryProtocol::TYPE_OF_MAIN
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', CategoryProtocol::TYPE_OF_MAIN);
        });
    }

}
