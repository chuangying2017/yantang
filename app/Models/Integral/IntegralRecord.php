<?php

namespace App\Models\Integral;

use App\Models\Access\User\UserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegralRecord extends Model
{
    use SoftDeletes;

    protected $table = 'integral_record';

    protected $guarded = ['id'];

    public function userProvider()
    {
        return $this->belongsTo(UserProvider::class,'user_id','user_id');
    }
}
