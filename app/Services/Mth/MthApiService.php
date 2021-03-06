<?php
/*
 * 每天惠API
 */
namespace App\Services\Mth;

use App\Services\Utilities\CacheHelper;
use App\Services\Utilities\HttpHelper;


class MthApiService
{
    /**
     * 基础url
     */
    const BASE_URL = "topapi/";
    /**
     * 获取token url
     */
    const AUTH_TOKEN_URL = "token";

    /**
     * 信息状态
     */
    const ACCOUNT_EXISTED = 'ACCOUNT EXISTED';
    const REGISTER_ERROR = 'REGISTER_ERROR';

    /**
     * @var basic auth token
     */
    protected static $access_token;

    /**
     * @var string appid
     */
    protected static $appid = MthConst::MTH_APPID;

    /**
     * @var string appsecret
     */
    protected static $appsecret = MthConst::MTH_APPSECRET;

    /**
     * get api url
     * @return string
     */
    protected static function getApiUrl()
    {
        return MthConst::MTH_API_URL . self::BASE_URL;
    }

    /**
     * cache the access token
     * @param $data
     * @return bool
     */
    protected static function setAccessToken($data)
    {
        if (!$data['access_token'] || !$data['expires_in']) return false;
        self::$access_token = $data['access_token'];
        CacheHelper::setCache('mth_access_token', $data['token'], $data['expires_in']);
        CacheHelper::setCache('mth_access_token_expire', time() + $data['expires_in'], $data['expires_in']);
        return self::$access_token;
    }

    /**
     * check the cache for access token
     * @return mixed|void
     */
    protected static function checkAccessToken()
    {
        if (CacheHelper::getCache('mth_access_token')) {
            if (CacheHelper::getCache('mth_access_token_expire') - time() > 0) {
                return CacheHelper::getCache('mth_access_token');
            } else {
                return self::getAccessToken();
            }
        } else {
            return self::getAccessToken();
        }
    }

    /**
     * get basic auth token
     */
    protected static function getAccessToken()
    {
        $result = HttpHelper::http_get(self::getApiUrl() . AUTH_TOKEN_URL . "?appid=" . self::$appid . "&secret=" . self::$appsecret);
        if ($result) {
            $data = json_decode($result, true);
            return self::setAccessToken($data);
        }

        throw new MthApiException('GET ACCESS TOKEN ERROR');
    }

    /**
     * login and get the user token
     * @param $account
     * @param $password
     * @return bool user_token
     * @throws MthApiException
     */
    protected static function loginGetToken($account, $password)
    {
        if (!self::$access_token && !self::checkAccessToken()) return false;
        $data = [
            "method" => "user_passport.login",
            "login_account" => $account,
            "login_password" => $password
        ];
        $result = HttpHelper::http_post(self::getApiUrl(), $data);
        if ($result) {
            $data = json_decode($result);
            if ($data['access_token']) {
                return $data['access_token'];
            } else {
                throw new MthApiException('NOT FOUNT ACCESS TOKEN');
            }
        } else {
            throw new MthApiException('LOGIN ERROR');
        }
    }

    /**
     * @param $account
     * @param $password
     * @return array|bool|mixed
     * @throws MthApiException
     */
    protected static function registerUser($account, $password)
    {
        if (!self::$access_token && !self::checkAccessToken()) return false;
        $data = [
            "method" => "user_passport.create",
            "login_account" => $account,
            "login_password" => $password
        ];
        $result = HttpHelper::http_post(self::getApiUrl(), $data);
        if ($result) {
            $data = json_decode($result);
            return $data;
        }

        throw new MthApiException('REGISTER USER ERROR');
    }

    /**
     * @param $account
     * @param $password
     * @return array|mixed
     * @throws MthApiException
     */
    protected static function getUserInfo($account, $password)
    {
        $user_token = self::loginGetToken($account, $password);
        $data = [
            "method" => "user_passport.query",
            "login_account" => $account,
            "access_token" => $user_token
        ];
        $result = HttpHelper::http_post(self::getApiUrl(), $data);
        if ($result) {
            $data = json_decode($result);
            return $data;
        }

        throw new MthApiException('GET USER INFO ERRROR');
    }

    /**
     * check the account existed or not
     * @param $login_account
     * @return bool
     */
    protected static function existed($login_account)
    {
        //todo@bryant
        return true;
    }

    /**
     * register a user on mth and get the userinfo then return
     * @param $account
     * @param $password
     * @return array|mixed
     */
    public static function registerGetUser($account, $password)
    {
        return 1; //for testing


        /**
         * check duplicate
         */
        if (self::existed($account)) {
            return self::getUserInfo($account, $password);
        } else {

            /**
             * register the user
             */
            if (self::registerUser($account, $password)) {
                /**
                 * return user info
                 */
                return self::getUserInfo($account, $password);
            } else {
                return self::REGISTER_ERROR;
            }

        }
    }
}
