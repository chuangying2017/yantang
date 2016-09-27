<?php namespace App\Repositories\Counter\Unit;

use App\Models\Counter\DailyCounter;
use App\Models\Counter\UnitCounter;
use App\Repositories\Counter\CounterProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DailyCounterRepository extends UnitCounterRepositoryAbstract {

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return DailyCounter::query();
    }

    /**
     * @param $counter_id
     * @return UnitCounter
     */
    public function getCounterTime($time = null)
    {
        return $time ? Carbon::parse($time)->toDateString() : Carbon::today()->toDateString();
    }

    public function getType()
    {
        return CounterProtocol::TYPE_UNIT_COUNTER_TYPE_OF_DAY;
    }
}
