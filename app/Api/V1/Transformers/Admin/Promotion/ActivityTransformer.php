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

            'share_image' => $activity['share_image'],
            'share_friend_title' => $activity['share_friend_title'],
            'share_desc' => $activity['share_desc'],
            'share_moment_title' => $activity['share_moment_title'],
            'can_share' => $activity['can_share'],
            
            'status' => $activity['status'],
        ];

        return $data;
    }

    public function includeCoupons(Activity $activity)
    {
        return $this->collection($activity->coupon_list, new CouponTransformer(), true);
    }

}
