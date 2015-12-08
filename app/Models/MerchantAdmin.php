<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantAdmin extends Model
{
    use SoftDeletes;

    protected $table = 'merchant_admins';
    protected $primaryKey = 'user_id';

}
