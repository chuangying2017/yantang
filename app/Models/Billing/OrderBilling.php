<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderBilling extends Model {

    use SoftDeletes;
    protected $table = 'order_billing';

    protected $guarded = ['id'];


}
