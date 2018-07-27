<?php

namespace App\Models\Integral;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegralUserAddress extends Model
{
    use SoftDeletes;

    protected $table = 'integral_user_address';

    protected $guarded = ['id'];

    public function user()
    {
      return  $this->belongsTo(User::class);
    }
}
