<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 5:01 PM
 */

namespace App\Services\Client;

use App\Models\Client;
use App\Repositories\Backend\Role\EloquentRoleRepository;
use App\Repositories\Frontend\User\EloquentUserRepository;
use App\Services\Client\Exceptions\ClientException;
use App\Services\Mth\MthApiService;

/**
 * Class ClientRepository
 * @package App\Services\Client
 */
class ClientRepository
{

    /**
     *
     */
    const CREATE_USER_ERROR = "create client error";

    /**
     * create a new client
     * @param $username
     * @param $password
     * @param $email
     * @param $phone
     * @return static
     * @throws ClientException
     */
    public static function create($username, $password, $email, $phone)
    {
        /**
         * sync from mth
         */
        $info = MthApiService::registerGetUser($username, $password, $email, $phone = null);
        if ($info) {
            $role = (new EloquentRoleRepository)->getDefaultUserRole();
            $userRepo = new EloquentUserRepository($role);

            $user = $userRepo->create([
                'name' => $username,
                'email' => $email,
                'password' => $password
            ]);

            $client = Client::create([
                'user_id' => $user->id
            ]);
            return $client;
        } else {
            throw new ClientException(self::CREATE_USER_ERROR);
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    public static function show($id)
    {
        $client = Client::findOrFail($id);
        return $client;
    }

    /**
     * @return mixed
     */
    public static function all()
    {
        return Client::where('status', 1)->get();
    }

    /**
     * update a user by id
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        $client = Client::findOrFail($id);
        $data = array_only($data, ['nickname', 'birthday', 'avatar', 'sex', 'status']);
        $client->update($data);
        return 1;
    }

    /**
     * delete a user by id
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {

    }


}
