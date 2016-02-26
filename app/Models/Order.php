<?php

namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Order extends Model {

    use SearchableTrait;

    use SoftDeletes;

    protected $table = 'orders';

    protected $guarded = ['id'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'orders.order_no'  => 10,
            'orders.last_name' => 10,
            'users.bio'        => 2,
            'users.email'      => 5,
            'posts.title'      => 2,
            'posts.body'       => 1,
        ],
        'joins'   => [
            'skus' => ['orders.id', 'skus.order_id'],
            'address' => ['orders.']
        ],
    ];

    public function children()
    {
        return $this->hasMany('App\Models\ChildOrder', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function skus()
    {
        return $this->hasMany('App\Models\OrderProductView', 'order_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id', 'id');
    }

    public function billings()
    {
        return $this->hasMany('App\Models\OrderBilling', 'order_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(PingxxPayment::class, 'order_id', 'id');
    }


}
