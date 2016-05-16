<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 5:01 PM
 */

namespace App\Services\Client;

use App\Models\Client\Client;
use App\Models\Client\Account\Credits;
use App\Models\Client\Account\Wallet;
use App\Repositories\Backend\Role\EloquentRoleRepository;
use App\Repositories\Frontend\User\EloquentUserRepository;
use App\Services\Agent\AgentService;
use App\Services\Client\Exceptions\ClientException;
use App\Services\Mth\MthApiService;

/**
 * Class ClientRepository
 * @package App\Services\Client
 */
class ClientRepository {

    /**
     *
     */
    const CREATE_USER_ERROR = "create client error";
    const DEFAULT_AVATAR = 'http://7xpdx2.com2.z0.glb.qiniucdn.com/default.jpeg';

    /**
     * create a new client
     * @param $username
     * @param $password
     * @param $email
     * @param $phone
     * @return static
     * @throws ClientException
     */
    public static function create($user, $extra_data = null)
    {
        //create a client
        Client::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id'      => $user->id,
                'nickname'     => array_get($extra_data, 'nickname', $user->name),
                'avatar'       => array_get($extra_data, 'avatar', self::DEFAULT_AVATAR),
                'promotion_id' => AgentService::getPromotionId(array_get($extra_data, 'promotion_code', null)),
            ]
        );
        //create wallet
        Wallet::firstOrCreate(['user_id' => $user->id]);
        //create creditWallet
        Credits::firstOrCreate(['user_id' => $user->id]);

        return ['user_id' => $user->id];
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function show($user_id, $relation = ['user'])
    {
        $client = Client::with($relation)->where('user_id', $user_id)->first();

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
        $client->fill($data);
        $client->save();

        return $client;
    }

    /**
     * delete a user by id
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {

    }

    public static function getByPromotionId($promotion_id, $paginate = 20)
    {
        $query = Client::with('user')->where('promotion_id', $promotion_id);

        if ( ! is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public static function getCountByPromotionId($promotion_id, $paginate = 20)
    {
        return Client::where('promotion_id', $promotion_id)->count();
    }


}
