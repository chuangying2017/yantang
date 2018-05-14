<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Auth\UserInfoTransformer;
use App\Models\Monitors;
use App\Repositories\Auth\User\UserContract;
use App\Repositories\Client\ClientRepositoryContract;
use Dingo\Api\Facade\Route;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

    /**
     * @var UserContract
     */
    private $userRepo;
    /**
     * @var ClientRepositoryContract
     */
    private $clientRepo;

    /**
     * UserController constructor.
     * @param UserContract $userRepo
     * @param ClientRepositoryContract $clientRepo
     */
    public function __construct(UserContract $userRepo, ClientRepositoryContract $clientRepo)
    {
        $this->userRepo = $userRepo;
        $this->clientRepo = $clientRepo;
    }

    /**
     * Display a listing of the resource.
     * 获取用户信息
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo()
    {
        $user_info = $this->userRepo->getUserInfo(access()->user(), true, true);
        return $this->response->item($user_info, new UserInfoTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserInfo(Request $request)
    {
        $client = $this->clientRepo->updateClient(access()->id, $request->all());

        return $this->getUserInfo();
    }


    public function weixinInfo()
    {
        $openId = access()->getProviderId();

        $info = \EasyWeChat::user()->get($openId);

        return $this->response->array(['data' => [
            'subscribe' => $info['subscribe'],
            'openid' => $info['openid']
        ]]);

    }


}
