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


    const SORT_SPLICE = ',';

    public static function decodeSort($sort = null)
    {
        if ( ! is_null($sort) && $sort) {
            $sort = explode(self::SORT_SPLICE, $sort);
            if (count($sort) == 2) {
                return [
                    'order_by'   => $sort[0],
                    'order_type' => self::getSortType($sort[1])
                ];
            }
        }

        return [
            'order_by'   => null,
            'order_type' => null
        ];
    }

    public static function getSortType($type)
    {
        $available = ['desc', 'asc'];

        return in_array($type, $available) ? $type : 'desc';
    }


}
