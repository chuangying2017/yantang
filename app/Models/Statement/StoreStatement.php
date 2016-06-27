<?php

namespace App\Models\Statement;


use App\Services\Statement\StatementProtocol;
use Illuminate\Database\Eloquent\Builder;

class StoreStatement extends StatementAbstract {

    protected $attributes = [
        'type' => StatementProtocol::TYPE_OF_STORE
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', StatementProtocol::TYPE_OF_STORE);
        });
    }

}
