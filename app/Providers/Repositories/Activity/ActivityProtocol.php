<?php namespace App\Repositories\Activity;

use Carbon\Carbon;

class ActivityProtocol {

    const PER_PAGE = 20;
    
    const ACTIVITY_STATUS_OF_OK = 1;
    const ACTIVITY_STATUS_OF_DRAFT = 2;
    
    const ACTIVITY_SHARE_OF_OK = 1;
    const ACTIVITY_SHARE_OF_NONE = 2;

    const NAME_STATUS_OF_OK = 'ok';
    const NAME_STATUS_OF_COMING = 'coming';
    const NAME_STATUS_OF_EXPIRED = 'expired';

    public static function statusName($status, $start_time, $end_time)
    {
        if ($status == self::ACTIVITY_STATUS_OF_OK) {
            if ($start_time > Carbon::today()) {
                return self::NAME_STATUS_OF_COMING;
            } else if ($end_time < Carbon::today()) {
                return self::NAME_STATUS_OF_EXPIRED;
            } else {
                return self::NAME_STATUS_OF_OK;
            }
        }

        return self::NAME_STATUS_OF_EXPIRED;
    }

}
