<?php namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionScope;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    use PromotionScope;

    protected $table = 'tickets';

    protected $guarded = ['id'];

    /*************************************************************
     * Relations
     **************************************************************/

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'promotion_id', 'id');
    }

}
