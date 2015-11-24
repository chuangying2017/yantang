<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 7:02 PM
 */

namespace App\Services\User;


use App\Models\Address;

class UserAddressRepository
{
    public static function create($data)
    {
        Address::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'province' => $data['province'],
            'city' => $data['city'],
            'detail' => $data['detail'],
            'role' => $data['role'],
            'display_name' => $data['display_name']
        ]);
    }

    public static function update($id, $data)
    {
        Address::find($id)->udpate($data);
    }

    public static function fetchByUser($user_id)
    {
        return Address::where('user_id', $user_id)->get();
    }

    public static function delete($id)
    {
        Address::find($id)->delete();
    }

}
