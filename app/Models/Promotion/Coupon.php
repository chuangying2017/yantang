<?php namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionRelations;
use App\Models\Promotion\Traits\PromotionScope;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class Coupon extends Model {

    use SoftDeletes, PromotionRelations, PromotionScope;

    protected $table = 'promotions';

    protected $guarded = ['id'];

    protected $attributes = [
        'type' => PromotionProtocol::TYPE_OF_COUPON
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', PromotionProtocol::TYPE_OF_COUPON);
        });
    }

    /*
     * Relations
     */

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'coupon_id', 'id');
    }

}
