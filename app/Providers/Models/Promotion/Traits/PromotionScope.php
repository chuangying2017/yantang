<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 6/15/16
 * Time: 11:13 AM
 */

namespace App\Models\Promotion\Traits;


use Carbon\Carbon;

trait PromotionScope {

    public function scopeEffect($query)
    {
        $query->where(function ($sub_query) {
            $sub_query->where('start_time', '<', Carbon::now())
                ->where('end_time', '>', Carbon::now());
        });
    }

}
