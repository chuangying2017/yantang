<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\Promotion\Coupon;
use App\Models\Promotion\Rule;

class OrderPromotion extends Model
{
    protected $table = 'order_promotions';

    protected $guarded = ['id'];

    public function promotion()
    {
        return $this->hasOne(Coupon::class, 'id', 'promotion_id');
    }

    public function rules()
    {
        return $this->belongsToMany(Rule::class, 'promotion_rules', 'promotion_rule_id', 'id');
    }
}
