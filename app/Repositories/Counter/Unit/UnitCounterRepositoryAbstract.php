<?php namespace App\Repositories\Counter\Unit;

use App\Models\Counter\Counter;
use App\Models\Counter\UnitCounter;
use Illuminate\Database\Eloquent\Builder;

abstract class UnitCounterRepositoryAbstract {

    public abstract function getType();

    /**
     * @return Builder
     */
    public abstract function getQuery();

    /**
     * @param $counter_id
     * @return UnitCounter
     */
    public abstract function getCounterTime($time = null);

    public function getCounter(Counter $counter, $time = null)
    {
        if (!($counter instanceof Counter)) {
            $counter = Counter::query()->find($counter);
        }

        return $this->getQuery()->firstOrCreate([
            'type' => $this->getType(),
            'counter_id' => $counter['id'],
            'source_type' => $counter['source_type'],
            'source_id' => $counter['source_id'],
            'time' => $this->getCounterTime($time)
        ]);
    }

    public function calUnitCounter(Counter $counter, $quantity, $amount, $increment = true, $time = null)
    {
        $unit_counter = $this->getCounter($counter, $time);
        if ($increment) {
            $unit_counter->quantity += $quantity;
            $unit_counter->amount += $amount;
        } else {
            $unit_counter->quantity = $unit_counter->quantity > $quantity ? $unit_counter->quantity - $quantity : 0;
            $unit_counter->amount = $unit_counter->amount > $amount ? $unit_counter->amount - $amount : 0;
        }

        $unit_counter->save();
        return $unit_counter;
    }

    public function setUnitCounter($counter_id, $quantity = null, $amount = null, $time = null)
    {
        $unit_counter = $this->getCounter($counter_id, $time);

        if (!is_null($quantity)) {
            $unit_counter->quantity = $quantity;
        }

        if (!is_null($amount)) {
            $unit_counter->amount = $amount;
        }
        $unit_counter->save();

        return $unit_counter;
    }


}
