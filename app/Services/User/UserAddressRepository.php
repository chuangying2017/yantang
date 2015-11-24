<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 7:02 PM
 */

namespace App\Services\User;


use App\Models\Address;
use App\Models\User;

class UserAddressRepository
{
    /**
     * create a new user address
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        $address = Address::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'province' => $data['province'],
            'city' => $data['city'],
            'detail' => $data['detail'],
            'role' => $data['role'],
            'display_name' => $data['display_name']
        ]);

        return $address;
    }

    /**
     * update an address
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        return Address::find($id)->udpate($data);
    }

    /**
     * get all addresses by user
     * @param $user_id
     * @return mixed
     */
    public static function fetchByUser($user_id)
    {
        $user = User::findOrFail($user_id);
        return $user->addresses();
    }

    /**
     * delete an address
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        return Address::destroy($id);
    }

}
