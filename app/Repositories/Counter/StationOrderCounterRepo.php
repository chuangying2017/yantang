<?php namespace App\Repositories\Counter;

use App\Models\Subscribe\Station;

class StationOrderCounterRepo extends CounterRepositoryAbstract {

    /**
     * @return string source_type
     */
    public function getType()
    {
        return CounterProtocol::COUNTER_TYPE_OF_STATION_PREORDER;
    }

    public function getName($source_id)
    {
        return Station::query()->where('id', $source_id)->pluck('name')->first();
    }
}
