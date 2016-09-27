<?php namespace App\Repositories\Counter\Unit;

use App\Models\Counter\UnitCounter;
use App\Models\Counter\WeeklyCounter;
use App\Repositories\Counter\CounterProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class WeeklyCounterRepository extends UnitCounterRepositoryAbstract {

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return WeeklyCounter::query();
    }

    /**
     * @param $counter_id
     * @return UnitCounter
     */
    public function getCounterTime($time = null)
    {
        return $time ? Carbon::parse($time)->year . Carbon::parse($time)->weekOfYear : Carbon::today()->year . Carbon::today()->weekOfYear;
    }

    public function getType()
    {
        return CounterProtocol::TYPE_UNIT_COUNTER_TYPE_OF_WEEK;
    }
}
