<?php namespace App\Repositories\Counter\Unit;
use App\Models\Counter\UnitCounter;
use App\Models\Counter\YearlyCounter;
use App\Repositories\Counter\CounterProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class YearlyCounterRepository extends UnitCounterRepositoryAbstract{

    public function getType()
    {
        return CounterProtocol::TYPE_UNIT_COUNTER_TYPE_OF_YEAR;
    }

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return YearlyCounter::query();
    }

    /**
     * @param $counter_id
     * @return UnitCounter
     */
    public function getCounterTime($time = null)
    {
        return $time ? Carbon::parse($time)->year : Carbon::today()->year;
    }
}
