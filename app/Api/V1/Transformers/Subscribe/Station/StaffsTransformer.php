<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\StationStaffs;

class StaffsTransformer extends TransformerAbstract
{

    public function transform(StationStaffs $staffs)
    {
        $data = [
            'id' => (int)$staffs->id,
            'name' => $staffs->name,
            'station_id' => $staffs->station_id,
            'user_id' => $staffs->user_id,
            'staff_no' => $staffs->staff_no,
            'phone' => $staffs->phone,
        ];

        if (isset($staffs->with_preorder) && $staffs->with_preorder) {
            $data['preorder'] = $staffs->preorders;
        }

        return $data;
    }

}
