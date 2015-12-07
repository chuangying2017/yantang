<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:49 AM
 */

namespace App\Services\User;


use App\Services\Product\Fav\FavService;

/**
 * Class UserService
 * @package App\Services\User
 */
class UserService
{
    /**
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function create($data)
    {
        return UserRepository::create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        return UserRepository::update($id, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        return UserRepository::delete($id);
    }

    /**
     * get a user by id
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return User::find($id)->get();
    }

    /**
     * get all users
     * @return mixed
     */
    public static function getAll()
    {
        return User::all();
    }

    /**
     *
     */
    public static function block()
    {

    }

    /**
     *
     */
    public static function active()
    {
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return int|string
     */
    public static function addFav($user_id, $product_id)
    {
        return FavService::create($user_id, $product_id);
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return int
     */
    public static function removeFav($user_id, $product_id)
    {
        return FavService::delete($user_id, $product_id);
    }
}
