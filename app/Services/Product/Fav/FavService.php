<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:01 PM
 */

namespace App\Services\Product\Fav;

/**
 * Class FavService
 * @package App\Services\Product\Fav
 */
class FavService {


    public static function lists($user_id, $paginate = null, $sort_name = 'created_at', $sort_type = 'desc')
    {
        return FavRepository::lists($user_id, $paginate, $sort_name, $sort_type);
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return int|string
     */
    public static function create($user_id, $product_id)
    {
        return FavRepository::create($user_id, $product_id);
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return int
     * @throws \Pheanstalk\Exception
     */
    public static function delete($user_id, $fav_id)
    {
        return FavRepository::delete($user_id, $fav_id);
    }

    public static function checkFav($user_id, $product_id)
    {
        return FavRepository::exits($user_id, $product_id);
    }

    /**
     * @param $product_id
     * @return int|string
     */
    public static function deleteCauseProductDeleted($product_id)
    {
        return FavRepository::delete(null, $product_id);
    }
}
