<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PingxxPayment extends Model {

    use SoftDeletes;

    protected $table = 'pingxx_payments';

    protected $guarded = ['id'];

}
