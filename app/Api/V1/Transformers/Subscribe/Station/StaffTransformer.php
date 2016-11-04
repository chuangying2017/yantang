<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\StationStaff;

class StaffTransformer extends TransformerAbstract {

    public function transform(StationStaff $staff)
    {
        $data = [
            'id' => (int)$staff->id,
            'staff_no' => $staff->staff_no,
            'name' => $staff->name,
            'user' => ['id' => $staff->user_id],
            'phone' => $staff->phone,
        ];

        
        if ($staff->relationLoaded('station')) {
            $this->defaultIncludes = ['station'];
        } else {
            $data['station'] = ['id' => $staff->station_id];
        }

        if (isset($staff['bind_token'])) {
            $data['bind_token'] = $staff['bind_token'];
        }

        return $data;
    }

    public function includeStation(StationStaff $staff)
    {
        if($staff['station']) {
            return $this->item($staff['station'], new StationTransformer(false), true);
        }
        return null;
    }

}
