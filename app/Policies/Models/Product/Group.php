<?php

namespace App\Models\Product;

use App\Repositories\Category\CategoryProtocol;
use Illuminate\Database\Eloquent\Builder;

class Group extends CategoryAbstract {

    protected $attributes = [
        'type' => CategoryProtocol::TYPE_OF_GROUP
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', CategoryProtocol::TYPE_OF_GROUP);
        });
    }

}
