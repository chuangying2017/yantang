<?php

namespace App\Models\Order;

use App\Models\Client\Address;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_address';

    protected $primaryKey = 'order_id';

    protected $guarded = ['id'];

}
