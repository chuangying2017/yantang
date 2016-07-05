<?php

namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model {

    use SoftDeletes;

    protected $table = 'stores';

    protected $guarded = ['id'];

    public function tickets()
    {
        return $this->hasMany(OrderTicket::class, 'store_id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'store_user', 'store_id', 'user_id');
    }
}
