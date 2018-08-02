<?php namespace App\Models\Promotion\Traits;

use App\Models\Integral\IntegralConvertCoupon;
use App\Models\Product\Product;
use App\Models\Product\ProductSku;
use App\Models\Promotion\PromotionCounter;
use App\Models\Promotion\PromotionDetail;
use App\Models\Promotion\PromotionSku;
use App\Models\Promotion\Rule;

trait PromotionRelations {

    public function detail()
    {
        return $this->hasOne(PromotionDetail::class, 'promotion_id', 'id');
    }

    public function rules()
    {
        return $this->belongsToMany(Rule::class, 'promotion_rule', 'promotion_id', 'rule_id');
    }

    public function counter()
    {
        return $this->hasOne(PromotionCounter::class, 'promotion_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promotion', 'promotion_id', 'product_id');
    }

    public function skus()
    {
        return $this->hasOne(PromotionSku::class, 'promotion_id', 'id');
    }

    public function convertCoupon()
    {
        return $this->hasMany(IntegralConvertCoupon::class,'promotions_id','id');
    }
}
