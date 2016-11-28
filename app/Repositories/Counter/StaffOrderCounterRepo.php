<?php namespace App\Repositories\Counter;

use App\Models\Subscribe\StationStaff;

class StaffOrderCounterRepo extends CounterRepositoryAbstract {

    /**
     * @return string source_type
     */
    public function getType()
    {
        return CounterProtocol::COUNTER_TYPE_OF_STAFF_PREORDER;
    }

    public function getName($source_id)
    {
        return StationStaff::query()->where('id', $source_id)->pluck('name')->first();
    }
}
