<?php

namespace App\Models\Client;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'addresses';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
