<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderMemo extends Model {

    protected $table = 'order_memo';

    protected $guarded = ['id'];

    protected $primaryKey = 'order_id';

}
