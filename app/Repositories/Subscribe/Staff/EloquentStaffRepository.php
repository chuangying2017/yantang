<?php namespace App\Repositories\Subscribe\Staff;

use App\Models\Subscribe\StationStaff;
use Carbon\Carbon;
use Pheanstalk\Exception;
use App\Services\Subscribe\PreorderProtocol;

class EloquentStaffRepository implements StaffRepositoryContract
{
    public function getStaffPaginated($station_id, $per_page, $order_by = 'id', $sort = 'asc')
    {
        $query = StationStaff::query();
        if (!empty($station_id)) {
            $query = $query->where('station_id', $station_id);
        }
        if (!empty($order_by) && !empty($sort)) {
            $query = $query->orderBy($order_by, $sort);
        }
        if (!empty($per_page)) {
            $query = $query->paginate($per_page);
        } else {
            $query = $query->get();
        }
        return $query;
    }

    public function create($input)
    {
        $input['staff_no'] = uniqid('stf_');
        return StationStaff::create($input);
    }

    public function update($id, $input, $station_id)
    {
        $query = StationStaff::find('id', $id);
        if ($query->station_id != $station_id) {
            throw new \Exception('该配送员不属于当前服务部');
        }
        $query = $query->fill($input);
        return $query->save();
    }

    public function show($id)
    {
        try {
            return StationStaff::findOrFail($id);
        } catch (\Exception $e) {
            throw new Exception('该配送员不存在');
        }
    }


    public function destroy($id)
    {
        return StationStaff::destroy(to_array($id));
    }

    public function byUserId($user_id, $with_order = false)
    {
        $staff = StationStaff::where('user_id', '=', $user_id)->first();
        if ($with_order) {
            $status = PreorderProtocol::STATUS_OF_NORMAL;
            $charge_status = PreorderProtocol::STATUS_OF_ENOUGH;
            $staff = $staff->load(['preorders' => function ($query) use ($status, $charge_status) {
                $query->where('status', '=', $status)->where('charge_status', '=', $charge_status)->with('product')->with('product.sku');
            }]);
        }

        return $staff;
    }

    public function bindStaff($staff_id = null, $user_id = null)
    {
        $staff = StationStaff::findOrFail($staff_id);
        $staff->user_id = $user_id;
        $staff->save();
        return $staff;
    }

}
