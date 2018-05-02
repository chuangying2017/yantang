<?php

namespace App\Models\Promotion;

use App\Services\Promotion\PromotionProtocol;
use Illuminate\Database\Eloquent\Builder;

class Giftcard extends PromotionAbstract {

    const TYPE_OF_PROMOTION = PromotionProtocol::TYPE_OF_GIFTCARD;

    protected $attributes = [
        'type' => self::TYPE_OF_PROMOTION
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', self::TYPE_OF_PROMOTION);
        });
    }

}
