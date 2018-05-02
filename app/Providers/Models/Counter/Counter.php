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


    public function setAmount($amount)
    {
        $this->attributes['amount'] = $amount;
        $this->save();
        return $this;
    }

    public function setQuantity($quantity)
    {
        $this->attributes['quantity'] = $quantity;
        $this->save();
        return $this;
    }

    public function setUserCount($user_count)
    {
        $this->attributes['user_count'] = $user_count;
        $this->save();
        return $this;
    }

    public function setUserCountKpi($user_count_kpi)
    {
        $this->attributes['user_count_kpi'] = $user_count_kpi;
        $this->save();
        return $this;
    }

}
