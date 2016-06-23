<?php namespace App\Repositories\Subscribe\Preorder;

use App\Models\Subscribe\Preorder;
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

    public function byStationId($station_id)
    {
        return Preorder::where('station_id', $station_id)->first();
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
}