<?php namespace App\Repositories\Counter;

use App\Models\Counter\Counter;
use App\Repositories\Counter\Unit\DailyCounterRepository;

abstract class CounterRepositoryAbstract {

    /**
     * @var DailyCounterRepository
     */
    private $dailyCounter;

    /**
     * CounterAbstract constructor.
     * @param DailyCounterRepository $dailyCounter
     */
    public function __construct(DailyCounterRepository $dailyCounter)
    {
        $this->dailyCounter = $dailyCounter;
    }

    /**
     * @return string source_type
     */
    public abstract function getType();

    public abstract function getName($source_id);

    public function createCounter($source_id, $source_name = null)
    {
        return Counter::query()->updateOrCreate(
            [
                'source_type' => $this->getType(),
                'source_id' => $source_id,
            ],
            [
                'source_type' => $this->getType(),
                'source_id' => $source_id,
                'source_name' => is_null($source_name) ? $this->getName($source_id) : $source_name,
            ]
        );
    }

    public function getCounter($source_id, $create_if_not_exist = false)
    {
        if ($source_id instanceof Counter) {
            return $source_id;
        }

        $counter = Counter::query()
            ->where('source_id', $source_id)
            ->where('source_type', $this->getType())
            ->first();

        if (!$counter && $create_if_not_exist) {
            return $this->createCounter($source_id);
        }

        return $counter;
    }

    public function increment($source_id, $quantity = 1, $amount = 0, $daily = true)
    {
        $counter = $this->getCounter($source_id, true);
        if (!$counter) {
            return false;
        }

        if ($daily) {
            $this->dailyCounter->calUnitCounter($counter['id'], $quantity, $amount, true);
        }

        $counter->quantity += $quantity;
        $counter->amount += $amount;
        $counter->save();

        return $counter;
    }

    public function decrement($source_id, $quantity = 1, $amount = 0, $daily = true)
    {
        $counter = $this->getCounter($source_id, true);
        if (!$counter) {
            return false;
        }

        if ($daily) {
            $this->dailyCounter->calUnitCounter($counter['id'], $quantity, $amount, false);
        }

        $counter->quantity = $counter->quantity > $quantity ? $counter->quantity - $quantity : 0;
        $counter->amount = $counter->amount > $amount ? $counter->amount - $amount : 0;
        $counter->save();

        return $counter;
    }


}
