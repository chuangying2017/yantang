<?php namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model {

    use PromotionScope, SoftDeletes;

    protected $table = 'tickets';

    protected $guarded = ['id'];

    /*************************************************************
     * Relations
     **************************************************************/

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'promotion_id', 'id');
    }
    public function giftcard()
    {
        return $this->belongsTo(Giftcard::class, 'promotion_id', 'id');
    }
}
