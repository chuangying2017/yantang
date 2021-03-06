<?php

namespace App\Models\Counter;


use App\Repositories\Counter\CounterProtocol;
use Illuminate\Database\Eloquent\Builder;

class WeeklyCounter extends UnitCounter {

    protected $attributes = [
        'type' => CounterProtocol::TYPE_UNIT_COUNTER_TYPE_OF_WEEK
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', CounterProtocol::TYPE_UNIT_COUNTER_TYPE_OF_WEEK);
        });
    }
}
