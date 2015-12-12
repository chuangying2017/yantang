<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:01 PM
 */

namespace App\Services\Product\Fav;


use App\Models\Access\User\User;
use App\Models\Product;
use App\Models\ProductCollection;
use \Exception;

/**
 * Class FavRepository
 * @package App\Services\Product\Fav
 */
class FavRepository {

    /**
     * @param $user_id
     * @param $product_id
     * @return int|string
     */
    public static function create($user_id, $product_id)
    {
        return ProductCollection::updateOrCreate([
            'product_id' => $product_id,
            'user_id'    => $user_id
        ]);
    }

    public static function exits($user_id, $product_id)
    {
        return ProductCollection::where('user_id', $user_id)->where('product_id', $product_id)->count();
    }

    public static function lists($user_id, $paginate = null, $sort_name = 'created_at', $sort_type = 'desc')
    {
        $query = ProductCollection::with('product')->where('user_id', $user_id)->orderBy($sort_name, $sort_type);

        if ( ! is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }


    /**
     * @param $user_id
     * @param $product_id
     * @return int
     * @throws Exception
     */
    public static function deleteByProduct($product_id)
    {
        return ProductCollection::where('product_id', $product_id)->delete();
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return int
     * @throws Exception
     */
    public static function delete($user_id, $fav_id)
    {
        $id = to_array($fav_id);

        return ProductCollection::where('user_id', $user_id)->whereIn('id', $id)->delete();
    }


}
