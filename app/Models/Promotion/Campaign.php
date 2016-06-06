<?php

namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionRelations;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model {

    use SoftDeletes, PromotionRelations;

    protected $attributes = [
        'type' => PromotionProtocol::TYPE_OF_SPECIAL_CAMPAIGN
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', PromotionProtocol::TYPE_OF_SPECIAL_CAMPAIGN);
        });
    }

    protected $table = 'promotions';

    protected $guarded = ['id'];


}
