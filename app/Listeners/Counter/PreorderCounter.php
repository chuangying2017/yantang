<?php

namespace App\Listeners\Counter;

use App\Events\Preorder\AssignIsDelete;
use App\Events\Preorder\PreorderIsCancel;
use App\Repositories\Counter\StaffOrderCounterRepo;
use App\Repositories\Counter\StationOrderCounterRepo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreorderCounter {

    /**
     * @var StaffOrderCounterRepo
     */
    private $staffOrderCounterRepo;
    /**
     * @var StationOrderCounterRepo
     */
    private $stationOrderCounterRepo;

    /**
     * Create the event listener.
     *
     * @param StaffOrderCounterRepo $staffOrderCounterRepo
     * @param StationOrderCounterRepo $stationOrderCounterRepo
     */
    public function __construct(StaffOrderCounterRepo $staffOrderCounterRepo, StationOrderCounterRepo $stationOrderCounterRepo)
    {
        $this->staffOrderCounterRepo = $staffOrderCounterRepo;
        $this->stationOrderCounterRepo = $stationOrderCounterRepo;
    }

    /**
     * Handle the event.
     *
     * @param  AssignIsDelete $event
     * @return void
     */
    public function handle(AssignIsDelete $event)
    {
        //
    }

    public function increment(AssignIsDelete $event)
    {
        try {
            $assign = $event->assign;
            if ($assign['station_id']) {
                $this->stationOrderCounterRepo->increment($assign['station_id'], 1);
            }

            if ($assign['staff_id']) {
                $this->staffOrderCounterRepo->increment($assign['staff_id'], 1);
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    public function decrement(AssignIsDelete $event)
    {
        try {
            $assign = $event->assign;
            if ($assign['station_id']) {
                $this->stationOrderCounterRepo->decrement($assign['station_id'], 1);
            }

            if ($assign['staff_id']) {
                $this->staffOrderCounterRepo->decrement($assign['staff_id'], 1);
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    public function cancel(PreorderIsCancel $event)
    {
        try {
            $preorder = $event->order;

            if ($preorder['station_id']) {
                $this->stationOrderCounterRepo->decrement($preorder['station_id'], 1);
            }
            if ($preorder['staff_id']) {
                $this->staffOrderCounterRepo->decrement($preorder['staff_id'], 1);
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }


}
