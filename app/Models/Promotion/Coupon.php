<?php namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionRelations;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model {

    use SoftDeletes, PromotionRelations;

    protected $table = 'promotions';

    protected $guarded = ['id'];

    protected $attributes = [
        'type' => PromotionProtocol::TYPE_OF_COUPON
    ];

    /*
     * Relations
     */

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'coupon_id', 'id');
    }

}
