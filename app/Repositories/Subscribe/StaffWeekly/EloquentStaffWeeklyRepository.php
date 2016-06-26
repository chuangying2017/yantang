<?php namespace App\Repositories\Subscribe\StaffWeekly;

use App\Http\Traits\EloquentRepository;
use App\Models\Subscribe\StaffWeekly;
use App\Services\Subscribe\PreorderProtocol;

class EloquentStaffWeeklyRepository implements StaffWeeklyRepositoryContract
{
    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\StaffWeekly';
    }

    public function updateByOther($week_of_year, $preorder_id, $staff_id, $data)
    {
        $query = StaffWeekly::where('week_of_year', $week_of_year)->where('preorder_id', $preorder_id)->where('staff_id', $staff_id)->first();
        return $query->update($data);
    }

    public function pause($week_of_year, $preorder_id, $staff_id, $day_of_week)
    {
        $staff_weekly = StaffWeekly::where('week_of_year', $week_of_year)->where('preorder_id', $preorder_id)->where('staff_id', $staff_id)->first();

        $week_array = PreorderProtocol::weekPauseName($day_of_week);
        foreach ($week_array as $value) {
            $staff_weekly->$value = null;
        }

        $staff_weekly->save();
        return 1;
    }

    public function byStaffId($staff_id, $week_of_year, $column)
    {
        return StaffWeekly::where('staff_id', $staff_id)->where('week_of_year', $week_of_year)->get([$column, 'preorder_id', 'staff_id']);
    }

    public function getOneDayDelivery($week_of_year, $column, $tomorrow_column)
    {
        $array = ['preorder_id', 'staff_id'];
        if (!empty($column)) {
            $array[] = $column;
        }
        if (!empty($tomorrow_column)) {
            $array[] = $tomorrow_column;
        }
        return StaffWeekly::where('week_of_year', $week_of_year)->get($array);
    }

    public function getUserOneDayDelivery($preorder_id, $week_of_year, $week_name)
    {
        $staff_weekly = StaffWeekly::where('week_of_year', $week_of_year)->orderBy('created_at', 'desc')->where('preorder_id', $preorder_id)->lists($week_name)->first();
        return $staff_weekly;
    }
}