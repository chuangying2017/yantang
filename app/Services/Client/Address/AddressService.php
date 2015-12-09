<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:43 AM
 */

namespace App\Services\Client;

use App\Models\Client;


/**
 * Class AddressService
 * @package App\Services\User
 */
class AddressService
{
    /**
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        return AddressRepository::create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        return AddressRepository::update($id, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        return AddressRepository::delete($id);
    }

    /**
     * get all addresses by user
     * @param $user_id
     * @return mixed
     */
    public static function fetchByUser($user_id)
    {
        $client = Client::findOrFail($user_id);
        return $client->addresses();
    }
}
