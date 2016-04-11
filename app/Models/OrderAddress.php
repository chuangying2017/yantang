<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Address
{
    protected $table = 'order_address';

    protected $guarded = ['id'];

}
