<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderOrder extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorder_preorder_orders';

    public function preorder()
    {
        return $this->belongsTo('App\Models\Subscribe\preorder');
    }

    public function staff()
    {
        return $this->hasMany(PreorderOrderProducts::class, 'preorder_order_id');
    }

}
