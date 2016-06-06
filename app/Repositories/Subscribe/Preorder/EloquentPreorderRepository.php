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
        $input['address'] = $input['area'] . $input['address'];
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
        if ($input['status'] == PreorderProtocol::STATUS_OF_PAUSE) {
            $input['pause_time'] = Carbon::now();
        } elseif ($input['status'] == PreorderProtocol::STATUS_OF_NORMAL) {
            $input['restart_time'] = Carbon::now();
        }
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
}