<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\StaffPreorder;

class StaffPreorderTransformer extends TransformerAbstract
{

    public function transform(StaffPreorder $staff_preorder)
    {
        $data = [
            'id' => (int)$staff_preorder->id,
            'preorder_id' => $staff_preorder->preorder_id,
            'station_id' => $staff_preorder->station_id,
            'staff_id' => $staff_preorder->staff_id,
            'index' => $staff_preorder->index,
            'preorder' => $staff_preorder->preorder,
        ];

        return $data;
    }

}
