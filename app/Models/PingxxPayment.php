<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PingxxPayment extends Model {

    protected $table = 'pingxx_payments';

    protected $guarded = ['id'];

}
