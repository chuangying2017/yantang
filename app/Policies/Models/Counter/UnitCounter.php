<?php

namespace App\Models\Counter;

use Illuminate\Database\Eloquent\Model;

abstract class UnitCounter extends Model {

    protected $guarded = [];

    protected $table = 'unit_counters';

    
}
