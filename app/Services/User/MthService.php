<?php

namespace App\Services\User;

use App\Http\Traits\CacheHelpers;
use App\Http\Traits\HttpHelpers;
use App\Services\CommonConst;


class MthApiService
{
    use HttpHelpers;
    use CacheHelpers;

    protected $appid;
    protected $appsecret;
    protected $apiurl;

    protected $access_token;
    protected $user_token;

    const BASE_URL = "topapi/";
    const AUTH_TOKEN_URL = "token";

    public function __construct()
    {
        $this->appid = CommonConst::MTH_APPID;
        $this->appsecret = CommonConst::MTH_APPSECRET;
        $this->apiurl = CommonConst::MTH_API_URL . BASE_URL;

        $this->checkAccessToken();
    }

    /**
     * @param $data
     */
    private function setAccessToken($data)
    {
        if(!$data['access_token'] || !$data['expires_in']) return false;
        $this->access_token = $data['access_token'];
        $this->setCache('mth_access_token', $data['token'],  $data['expires_in']);
        $this->setCache('mth_access_token_expire', time() + $data['expires_in'], $data['expires_in']);
        $this->apiurl = $this->apiurl . "?access_token=" . $this->access_token;
        return $this->access_token;
    }

    private function checkAccessToken()
    {
        if($this->getCache('mth_token')){
            if($this->getCache('mth_token_expire') - time() > 0){
                return $this->getCache('mth_token');
            }else{
                return $this->getAccessToken();
            }
        }else{
            return $this->getAccessToken();
        }
    }

    /**
     * get basic auth token
     */
    private function getAccessToken()
    {
        $result = $this->http_get($this->apiurl . AUTH_TOKEN_URL . "?appid=" . $this->appid . "&secret=" . $this->appsecret);
        if($result){
            $data = json_decode($result, true);
            $this->setAccessToken($data);
        }
    }

    /**
     * login user
     * @param $account login account
     * @param $password login password
     */
    private function loginGetToken($account, $password)
    {
        if (!$this->access_token && !$this->checkAccessToken()) return false;
        $data = [
            "method" => "user_passport.login",
            "login_account" => $account,
            "login_password" => $password
        ];
        $result = $this->http_post($this->apiurl, $data);
        if($result){
            $data = json_decode($result);
            if($data['access_token']){
                return $data['access_token'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /**
     * @param $account
     * @param $password
     * @return array|bool|mixed
     */
    public function registerUser($account, $password)
    {
        if (!$this->access_token && !$this->checkAccessToken()) return false;
        $data = [
            "method" => "user_passport.create",
            "login_account" => $account,
            "login_password" => $password
        ];
        $result = $this->http_post($this->apiurl, $data);
        if($result){
            $data = json_decode($result);
            return $data;
        }
    }

    /**
     * get user information from mth
     * @return bool
     */
    public function getUserInfo($account, $password)
    {
        $user_token = $this->loginGetToken($account, $password);
        $data = [
            "method" => "user_passport.query",
            "login_account" => $account,
            "access_token" => $user_token
        ];
        $result = $this->http_post($this->apiurl, $data);
        if($result){
            $data = json_decode($result);
            return $data;
        }
    }
}
