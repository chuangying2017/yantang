<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 5:01 PM
 */

namespace App\Services\User;

use App\Library\Wechat\Exceptions\WechatException;
use App\Models\User;
use App\Services\Mth\MthApiService;

class UserRepository
{

    const CREATE_USER_ERROR = "create user error";

    protected $wallet;
    protected $creditWallet;
    protected $addressManager;

    /**
     * UserRepository constructor.
     * @param $wallet
     * @param $creditWallet
     * @param $addressManager
     */
    public function __construct($wallet, $creditWallet, $addressManager)
    {
        $this->wallet = $wallet;
        $this->creditWallet = $creditWallet;
        $this->addressManager = $addressManager;
    }

    /**
     * create a new user
     * @param $data
     * @throws \Exception
     */
    public static function create($data)
    {
        /**
         * sync from mth
         */
        $info = MthApiService::registerGetUser($data['account'], $data['password']);
        if ($info) {
            $user = User::create([
                "user_id" => $info['user_id'],
                "login_account" => $info['login_account'] || $data['account'],
                "password" => Hash::make($data['password']),
                "useranme" => $info['useranme'],
                "name" => $info['name'],
                "avatar" => $info['avatar'],
                "sex" => $info['sex'],
                "tel" => $info['tel'],
                "email" => $info['email'],
                "mobile" => $info['mobile'],
                "user_grade" => $info['user_grade'],
                "gift_ticket" => $info['gift_ticket'],
                "birthday" => $info['birthday'],
                "createtime" => $info['createtime'] || date('Y-m-d H:i:s'),
            ]);

            return $user;
        } else {
            throw new \Exception(CREATE_USER_ERROR);
        }
    }

    /**
     * update a user by id
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        return User::find($id)->update($data);
    }

    /**
     * delete a user by id
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        return User::find($id)->delete();
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
}
