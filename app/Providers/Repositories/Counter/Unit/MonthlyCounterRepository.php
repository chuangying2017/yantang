<?php namespace App\Repositories\Counter\Unit;

use App\Models\Counter\MonthlyCounter;
use App\Models\Counter\UnitCounter;
use App\Repositories\Counter\CounterProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class MonthlyCounterRepository extends UnitCounterRepositoryAbstract {

    public function getType()
    {
        return CounterProtocol::TYPE_UNIT_COUNTER_TYPE_OF_MONTH;
    }

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return MonthlyCounter::query();
    }

    /**
     * @param $counter_id
     * @return UnitCounter
     */
    public function getCounterTime($time = null)
    {
        return $time ? Carbon::parse($time)->format('Y-m') : Carbon::today()->format('Y-m');
    }
}
