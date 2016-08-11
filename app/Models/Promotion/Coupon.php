<?php namespace App\Models\Promotion;

use App\Services\Promotion\PromotionProtocol;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends PromotionAbstract {

    const TYPE_OF_PROMOTION = PromotionProtocol::TYPE_OF_COUPON;
    
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
    
    /*
     * Relations
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'coupon_id', 'id');
    }

}
