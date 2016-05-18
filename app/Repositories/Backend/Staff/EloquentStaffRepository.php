<?php namespace App\Repositories\Backend\Staff;

use App\Models\Subscribe\StationStaffs;
use Pheanstalk\Exception;

class EloquentStaffRepository implements StaffRepositoryContract
{
    public function getStaffPaginated($station_id, $per_page, $order_by = 'id', $sort = 'asc')
    {
        $query = StationStaffs::query();
        if (!empty($station_id)) {
            $query->find($station_id);
        }
        if (!empty($order_by) && !empty($sort)) {
            $query = $query->orderBy($order_by, $sort);
        }
        if (!empty($per_page)) {
            $query = $query->paginate($per_page);
        }
        return $query;
    }

    public function create($input)
    {
        $input['staff_no'] = uniqid('stf_');
        return StationStaffs::create($input);
    }

    public function update($id, $input, $station_id)
    {
        $query = StationStaffs::find('id', $id);
        if ($query->station_id != $station_id) {
            throw new \Exception('该配送员不属于当前服务部');
        }
        $query = $query->fill($input);
        return $query->save();
    }

    public function show($id)
    {
        try {
            return StationStaffs::findOrFail($id);
        } catch (\Exception $e) {
            throw new Exception('该配送员不存在');
        }
    }


    public function destroy($id)
    {
        //todo 补充关联的删除
        $station = StationStaffs::findOrFail($id);
        $station->delete();
    }
}