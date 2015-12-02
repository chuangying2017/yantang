<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:49 AM
 */

namespace App\Services\User;


class UserService
{
    public static function create($data)
    {
        return UserRepository::create($data);
    }

    public static function update($id, $data)
    {
        return UserRepository::update($id, $data);
    }

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

    public static function block()
    {

    }

    public static function active()
    {
    }
}
