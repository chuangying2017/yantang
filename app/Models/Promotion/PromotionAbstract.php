<?php

namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionRelations;
use App\Models\Promotion\Traits\PromotionScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionAbstract extends Model {

    use SoftDeletes, PromotionRelations, PromotionScope;

    const TYPE_OF_PROMOTION = null;

    protected $table = 'promotions';

    protected $guarded = ['id'];

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
