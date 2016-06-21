<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderOrder extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorder_orders';

    public function preorder()
    {
        return $this->belongsTo('App\Models\Subscribe\preorder');
    }

    public function staff()
    {
        return $this->hasMany(PreorderOrderProducts::class, 'preorder_order_id');
    }

    public function product()
    {
        return $this->hasMany(PreorderOrderProducts::class, 'preorder_order_id');
    }

    public function orderBillings()
    {
        return $this->hasMany(PreorderOrderBillings::class, 'preorder_order_id');
    }

}
