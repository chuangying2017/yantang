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
        $query = StaffWeekly::where('week_of_year', $week_of_year)->where('preorder_id', $preorder_id)->where('staff_id', $staff_id);
        return $query->update($data);
    }

    public function pause($week_of_year, $preorder_id, $staff_id, $day_of_week)
    {
        $query = StaffWeekly::where('week_of_year', $week_of_year)->where('preorder_id', $preorder_id)->where('staff_id', $staff_id);
        $week_array = PreorderProtocol::weekPauseName($day_of_week);
        $data = [];
        foreach ($week_array as $value) {
            $data[$value] = json_encode([]);
        }
        return $query->update($data);
    }

    public function byStaffId($staff_id, $week_of_year, $column)
    {
        return StaffWeekly::where('staff_id', $staff_id)->where('week_of_year', $week_of_year)->get([$column, 'preorder_id', 'staff_id']);
    }
}