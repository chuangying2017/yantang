<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class ChargeBilling extends Model
{
    protected $table = 'charge_billings';

    protected $guarded = ['id'];
}
