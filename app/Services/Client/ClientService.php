<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:49 AM
 */

namespace App\Services\Client;


use App\Services\Product\Fav\FavService;
use Qiniu\Http\Client;

/**
 * Class UserService
 * @package App\Services\User
 */
class ClientService {

    /**
     * @param $username
     * @param $password
     * @param $email
     * @param null $phone
     * @return static
     * @throws Exceptions\ClientException
     */
    public static function create($user, $extra_data = null)
    {
        return ClientRepository::create($user, $extra_data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        return ClientRepository::update($id, $data);
    }


    /**
     * get a user by id
     * @param $id
     * @return mixed
     */
    public static function show($id)
    {
        return ClientRepository::show($id);
    }

    /**
     * get all users
     * @return mixed
     */
    public static function getAll()
    {
        return ClientRepository::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function block($id)
    {
        return ClientRepository::update($id, ['status' => 0]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function unblock($id)
    {
        return ClientRepository::update($id, ['status' => 1]);
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
