<?php

namespace App\Models\Client;

use App\Models\Access\User\User;
use App\Models\Client\Traits\BindUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model {

    use SoftDeletes, BindUser;

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
