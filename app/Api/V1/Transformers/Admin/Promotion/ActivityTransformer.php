<?php namespace App\Api\V1\Transformers\Admin\Promotion;

use App\Models\Promotion\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract {

    public function transform(Activity $activity)
    {
        if ($activity->coupon_list) {
            $this->defaultIncludes = ['coupons'];
        }

        $data = [
            'id' => $activity['id'],
            'url' => env('ACTIVITY_BASE_URL') . $activity['activity_no'],
            'activity_no' => $activity['activity_no'],
            'name' => $activity['name'],
            'priority' => $activity['priority'],
            'desc' => $activity['desc'],
            'cover_image' => $activity['cover_image'],
            'background_color' => $activity['background_color'],
            'start_time' => $activity['start_time'],
            'end_time' => $activity['end_time'],
            'status' => $activity['status'],
        ];

        return $data;
    }

    public function includeCoupons(Activity $activity)
    {
        return $this->collection($activity->coupon_list, new CouponTransformer(), true);
    }

}
