<?php

namespace App\Models\Client;

use App\Services\Client\ClientProtocol;
use Illuminate\Database\Eloquent\Builder;

class UserGroup extends UserGroupAbstract
{

    protected $attributes = [
        'type' => ClientProtocol::GROUP_TYPE_OF_NORMAL
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', ClientProtocol::GROUP_TYPE_OF_NORMAL)->orderBy('priority', 'desc');
        });
    }
}
