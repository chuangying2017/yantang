<?php namespace App\Api\V1\Transformers\Backend\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Station\StationStaffs;

class StaffsTransformer extends TransformerAbstract
{

    public function transform(StationStaffs $staffs)
    {
        $data = [
            'id' => (int)$staffs->id,
            'staff_no' => $staffs->staff_no,
            'name' => $staffs->name,
            'phone' => $staffs->phone,
        ];

        return $data;
    }

}
