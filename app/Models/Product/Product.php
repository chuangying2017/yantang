<?php

namespace App\Models\Product;

use App\Models\Product\Traits\ProductRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {

    use SoftDeletes, ProductRelation;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->orderBy('priority', 'asc');
        });
    }

    protected $table = 'products';

    protected $guarded = ['id'];

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function promotions()
    {
        return $this->belongsToMany('product_promotion', 'product_id', 'promotion_id');
    }
}
