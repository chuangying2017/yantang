<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Models\Subscribe\PreorderAssign;
use League\Fractal\TransformerAbstract;

class PreorderAssignTransformer extends TransformerAbstract {

    public function transform(PreorderAssign $assign)
    {
        return [
            'preorder' => ['id' => $assign['preorder_id']],
            'station' => ['id' => $assign['station_id']],
            'staff' => ['id' => $assign['staff_id']],
            'status' => $assign['status'],
            'time_before' => $assign['time_before'],
            'memo' => $assign['memo'],
            'confirm_at' => $assign['confirm_at'],
        ];
    }

}
