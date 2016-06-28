<?php namespace App\Repositories\Station\Staff;

use App\Models\Subscribe\StationStaff;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Station\StationProtocol;

class EloquentStaffRepository implements StaffRepositoryContract {

    public function createStaff($station_id, $staff_data)
    {
        return StationStaff::create([
            'station_id' => $station_id,
            'staff_no' => $station_id . mt_rand(100000, 999999),
            'name' => $staff_data['name'],
            'phone' => $staff_data['phone'],
            'status' => StationProtocol::STATUS_OF_STAFF_UN_BIND
        ]);
    }

    public function updateStaff($staff_id, $staff_data)
    {
        $staff = $this->getStaff($staff_id);
        $staff->fill(array_only($staff_data, [
            'station_id',
            'name',
            'phone',
            'status'
        ]));
        $staff->save();

        return $staff;
    }

    public function bindUser($staff_id, $user_id)
    {
        $staff = $this->getStaffByUser($user_id);
        if ($staff) {
            throw new \Exception('用户不能绑定多个配送员');
        }

        $staff = $this->getStaff($staff_id);
        $staff->user_id = $user_id;
        $staff->status = StationProtocol::STATUS_OF_STAFF_BIND;
        $staff->save();

        access()->addRole(AccessProtocol::ROLE_OF_STAFF);

        return $staff;
    }

    public function updateAsActive($staff_ids)
    {
        return StationStaff::query()->whereIn('id', to_array($staff_ids))->update(['status' => StationProtocol::STATUS_OF_STAFF_BIND]);
    }

    public function updateAsUnActive($staff_ids)
    {
        return StationStaff::whereIn('id', to_array($staff_ids))->update(['status' => StationProtocol::STATUS_OF_STAFF_BANNED]);
    }

    public function deleteStaff($staff_id)
    {

        return StationStaff::destroy($staff_id);
    }

    public function getStaff($staff_id, $with_user = false)
    {
        if ($staff_id instanceof StationStaff) {
            $staff = $staff_id;
        } else {
            $staff = StationStaff::query()->findOrFail($staff_id);
        }

        if ($with_user) {
            $staff->load('user');
        }
        return $staff;
    }

    public function getStaffByUser($user_id, $throw_error = false)
    {
        if ($throw_error) {
            return StationStaff::query()->where('user_id', $user_id)->firstOrFail();
        }

        return StationStaff::query()->where('user_id', $user_id)->first();
    }

    public function getAll($station_id, $status = null)
    {
        return $this->queryStaffs($station_id, $status);
    }

    public function getAllActive($station_id)
    {
        return $this->queryStaffs($station_id, StationProtocol::STATUS_OF_STAFF_BIND);
    }

    public function getAllUnActive($station_id)
    {
        return $this->queryStaffs($station_id, StationProtocol::STATUS_OF_STAFF_BANNED);
    }

    protected function queryStaffs($station_id = null, $status = null, $per_page = null)
    {
        #todo 缓存;
        $query = StationStaff::query();
        if (!is_null($station_id)) {
            $query->where('station_id', $station_id);
        }
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($per_page)) {
            return $query->paginate(20);
        }

        return $query->get();
    }


    public function unbindUser($staff_id, $user_id)
    {
        $staff = $this->getStaff($staff_id);

        $staff->user_id = 0;
        $staff->status = StationProtocol::STATUS_OF_STAFF_UN_BIND;
        $staff->save();

        access()->removeRole(AccessProtocol::ROLE_OF_STAFF);

        return $staff;
    }

    public function getStaffIdByUser($user_id)
    {
        $staff = $this->getStaffByUser($user_id);

        return $staff['id'];
    }

    public function getBindToken($staff_id)
    {
        return generate_bind_token($staff_id);
    }

}
