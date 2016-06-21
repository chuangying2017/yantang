<?php

namespace App\Models\Pay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PingxxPayment extends Model {

    use SoftDeletes;

    protected $table = 'pingxx_payments';

    protected $guarded = ['id'];

    public function billing()
    {
        return $this->morphTo('billing');
    }

}
