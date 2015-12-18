<?php namespace App\Services;
class ApiConst {

    /**
     * Backend
     */
    const COUPON_PER_PAGE = 20;
    const PRODUCT_PER_PAGE = 20;
    const FAV_PRE_PAGE = 20;
    const IMAGE_PER_PAGE = 20;


    /**
     * Frontend
     */


    const SORT_SPLICE = '_';

    public static function decodeSort($sort = null)
    {
        if ( ! is_null($sort) && $sort) {
            $sort = explode(self::SORT_SPLICE, $sort);
            if (count($sort) == 3 && $sort[0] == 'sort') {
                return [
                    'order_by'   => $sort[1],
                    'order_type' => $sort[2]
                ];
            }
        }

        return [
            'order_by'   => null,
            'order_type' => null
        ];
    }


}
