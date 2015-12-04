<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:01 PM
 */

namespace App\Services\Product\Fav;


use App\Models\Product;
use App\Models\User;
use Pheanstalk\Exception;

/**
 * Class FavRepository
 * @package App\Services\Product\Fav
 */
class FavRepository
{
    /**
     * @param $user_id
     * @param $product_id
     * @return int|string
     */
    public static function create($user_id, $product_id)
    {
        try {
            $user = User::find($user_id);
            if (!$user) {
                throw new Exception('USER NOT FOUND');
            }
            $product = Product::find($product_id);
            if (!$product) {
                throw new Exception('PRODUCT NOT FOUND');
            }
            $record = DB::table('user_product_favs')->where('product_id', $product_id)->where('user_id', $user_id)->count();
            if ($record > 0) {
                throw new Exception('RECORD EXISTED');
            }

            DB::table('user_product_favs')->insert([
                'product_id' => $product_id,
                'user_id' => $user_id
            ]);

            return 1;
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return int
     * @throws Exception
     */
    public static function delete($user_id, $product_id)
    {
        $record = DB::table('user_product_favs')->where('product_id', $product_id)->where('user_id', $user_id)->count();
        if ($record <= 0) {
            throw new Exception('RECORD NOT FOUND');
        }
        DB::table('user_product_favs')->where('product_id', $product_id)->where('user_id', $user_id)->delete();
        return 1;
    }
}
