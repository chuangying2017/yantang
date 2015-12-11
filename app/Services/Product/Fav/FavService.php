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
    public static function delete($user_id, $product_id)
    {
        return FavRepository::delete($user_id, $product_id);
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return bool
     */
    public static function isFavedByUser($user_id, $product_id)
    {
        return DB::table('user_product_favs')->where('user_id', $user_id)->where('product_id', $product_id)->count() > 0;
    }

    /**
     * @param $product_id
     * @return int|string
     */
    public static function deleteByProduct($product_id)
    {
        try {

            DB::table('user_product_favs')->where('product_id', $product_id)->delete();

            return 1;

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
