<?php

namespace App\Models\Collect;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'addresses';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        return $this->hasOne(AddressInfo::class, 'address_id', 'id');
    }

}
