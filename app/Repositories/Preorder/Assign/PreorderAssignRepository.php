<?php namespace App\Repositories\Preorder\Assign;

use App\Models\Subscribe\PreorderAssign;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;

class PreorderAssignRepository implements PreorderAssignRepositoryContract {

    public function get($order_id)
    {
        if ($order_id instanceof PreorderAssign) {
            return $order_id;
        }
        return PreorderAssign::query()->findOrFail($order_id);
    }

    public function createAssign($order_id, $station_id)
    {
        return PreorderAssign::create([
            'preorder_id' => $order_id,
            'station_id' => $station_id,
            'status' => PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED,
            'time_before' => Carbon::now()->addDays(PreorderProtocol::DAYS_OF_ASSIGN_DISPOSE)
        ]);
    }

    public function updateAssignAsConfirm($order_id)
    {
        $assign = $this->get($order_id);
        $assign->status = PreorderProtocol::ASSIGN_STATUS_OF_CONFIRM;
        $assign->confirm_at = Carbon::now();
        $assign->save();

        return $assign;
    }

    public function updateAssignAsReject($order_id, $memo = null)
    {
        $assign = $this->get($order_id);
        $assign->status = PreorderProtocol::ASSIGN_STATUS_OF_REJECT;
        $assign->memo = $memo;
        $assign->save();

        return $assign;
    }

    public function updateAssignStation($order_id, $station_id)
    {
        $assign = $this->get($order_id);
        $assign->station_id = $station_id;
        $assign->status = PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED;
        $assign->time_before = Carbon::now()->addDays(PreorderProtocol::DAYS_OF_ASSIGN_DISPOSE);
        $assign->save();

        return $assign;
    }

    public function updateAssignStaff($order_id, $staff_id)
    {
        $assign = $this->get($order_id);
        $assign->staff_id = $staff_id;
        $assign->save();

        return $assign;
    }

    public function deleteAssignStaff($order_id)
    {
        $assign = $this->get($order_id);
        $assign->staff_id = 0;
        $assign->save();

        return $assign;
    }
}
