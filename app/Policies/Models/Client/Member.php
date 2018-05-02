<?php

namespace App\Models\Client;


use App\Services\Client\ClientProtocol;
use Illuminate\Database\Eloquent\Builder;

class Member extends UserGroupAbstract {

    protected $type = ClientProtocol::GROUP_TYPE_OF_MEMBER;

    protected $attributes = [
        'type' => ClientProtocol::GROUP_TYPE_OF_MEMBER
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', ClientProtocol::GROUP_TYPE_OF_MEMBER)->orderBy('priority', 'desc');
        });
    }

}
