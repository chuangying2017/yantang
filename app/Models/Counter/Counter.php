<?php

namespace App\Models\Counter;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model {

    protected $table = 'counters';

    protected $guarded = [];

    public function daily()
    {
        return $this->hasMany(DailyCounter::class, 'counter_id', 'id')->orderBy('time', 'desc');
    }

    public function weekly()
    {
        return $this->hasMany(WeeklyCounter::class, 'counter_id', 'id')->orderBy('time', 'desc');
    }

    public function monthly()
    {
        return $this->hasMany(MonthlyCounter::class, 'counter_id', 'id')->orderBy('time', 'desc');
    }

    public function yearly()
    {
        return $this->hasMany(YearlyCounter::class, 'counter_id', 'id')->orderBy('time', 'desc');
    }

}
