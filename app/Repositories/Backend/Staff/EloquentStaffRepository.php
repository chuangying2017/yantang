<?php namespace App\Repositories\Backend\Staff;

use App\Models\Station\StationStaffs;
use Pheanstalk\Exception;

class EloquentStaffRepository implements StaffRepositoryContract
{
    public function getStaffPaginated($per_page, $order_by = 'id', $sort = 'asc')
    {
        return StationStaffs::orderBy($order_by, $sort)->paginate($per_page);
    }

    public function create($input)
    {
        return StationStaffs::create($input);
    }

    public function update($id, $input)
    {

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

    }
}