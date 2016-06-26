<?php namespace App\Repositories\Preorder;

use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\StaffPreorder;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Carbon\Carbon;
use App\Services\Subscribe\PreorderProtocol;

class EloquentPreorderRepository implements PreorderRepositoryContract
{

    public function moder()
    {
        return 'App\Models\Subscribe\Preorder';
    }

    public function create($input)
    {
        unset($input['area']);
        $input['order_no'] = uniqid('pre_');
        //是否需要充值 enough:不是 not_enough:是
        $input['charge_status'] = PreorderProtocol::STATUS_OF_NOT_ENOUGH;
        $input['status'] = PreorderProtocol::STATUS_OF_UNTREATED;
        return Preorder::create($input);
    }

    public function byUserId($user_id)
    {
        return Preorder::where('user_id', $user_id)->first();
    }

    public function byStationId($station_id, $status, $pre_page)
    {
        $query = Preorder::where('station_id', $station_id);
        switch ($status) {
            case PreorderProtocol::STATUS_OF_UNTREATED:
                $query = $query->where('status', '=', PreorderProtocol::STATUS_OF_UNTREATED)->with('user');
                break;
            case PreorderProtocol::STATUS_OF_NO_STAFF:
                $query = $query->where('status', '=', PreorderProtocol::STATUS_OF_NO_STAFF)->with('user');
                break;
            case PreorderProtocol::STATUS_OF_NORMAL:
                $query = $query->where('status', '=', PreorderProtocol::STATUS_OF_NORMAL)->with('user');
                break;
            case PreorderProtocol::STATUS_OF_NOT_ENOUGH:
                $query = $query->where('charge_status', '=', PreorderProtocol::STATUS_OF_NOT_ENOUGH)->with('user');
                break;
            default:
                break;
        }
        $query = $query->where('status', '!=', PreorderProtocol::STATUS_OF_REJECT);

        if (!empty($pre_page)) {
            $query = $query->paginate($pre_page);
        } else {
            $query = $query->get();
        }
        return $query;
    }

    public function update($input, $preorder_id)
    {
        $preorder = Preorder::find($preorder_id);
        $preorder->fill($input)->save();
        return $preorder;
    }

    public function byId($preorder_id, $with = [])
    {
        $query = Preorder::query();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->find($preorder_id);
    }

    public function searchInfo($per_page, $order_no, $begin_time, $end_time, $phone, $status)
    {
        $query = Preorder::query();
        if (!empty($order_no)) {
            $query = $query->where('order_no', $order_no);
        }

        if (!empty($begin_time)) {
            $query = $query->where('created_at', '>=', $begin_time);
        }

        if (!empty($end_time)) {
            $query = $query->where('created_at', '<=', $end_time);
        }

        if (!empty($phone)) {
            $query = $query->where('phone', $phone);
        }

        if (!empty($status)) {
            $query = $query->where('status', $status);
        }
        $query = $query->with(['district'])->orderBy('created_at', 'desc');

        if (!empty($per_page)) {
            $query = $query->paginate($per_page);
        } else {
            $query = $query->get();
        }

        return $query;
    }

    public function getOrderStaffId($preorder_id)
    {

        $staff_order_relation = StaffPreorder::where('preorder_id', $preorder_id)->firstOrFail();

        return $staff_order_relation->staff_id;
    }
}
