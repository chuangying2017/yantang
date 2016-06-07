<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorders';

    public function user()
    {
        return $this->belongsTo('App\Models\Access\User\User');
    }

    public function product()
    {
        return $this->hasMany('App\Models\Subscribe\PreorderProduct');
    }

    public function staffPreorder()
    {
        return $this->hasOne('App\Models\Subscribe\StaffPreorder');
    }

    public function preorderOrder()
    {
        return $this->hasOne(PreorderOrder::class, 'preorder_id');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\Subscribe\Station');
    }
}
