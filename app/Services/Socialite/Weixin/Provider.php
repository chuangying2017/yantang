<?php

namespace App\Services\Socialite\Weixin;

use App\Services\Socialite\ApiStateless;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class Provider extends AbstractProvider implements ProviderInterface {

    use ApiStateless;

    /**
     * {@inheritdoc}.
     */
    protected $openId;

    /**
     * {@inheritdoc}.
     */
    protected $scopes = ['snsapi_userinfo'];

    /**
     * {@inheritdoc}.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://open.weixin.qq.com/connect/oauth2/authorize', $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $session = $this->request->getSession();

        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);

        return $url . '?' . $query . '#wechat_redirect';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return [
            'appid' => $this->clientId,
            'redirect_uri' => $this->redirectUrl . '?role=' . \Request::input('role'),
            'response_type' => 'code',
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'state' => $state
        ];
    }

    protected function getRedirectUrl()
    {

    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenUrl()
    {
        return 'https://api.weixin.qq.com/sns/oauth2/access_token';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.weixin.qq.com/sns/userinfo', [
            'query' => [
                'access_token' => $token,
                'openid' => $this->openId,
                'lang' => 'zh_CN',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}.
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['openid'],
            'union_id' => array_get($user, 'unionid', null),
            'nickname' => $user['nickname'],
            'avatar' => $user['headimgurl'],
            'name' => null,
            'email' => null,
        ]);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenFields($code)
    {
        return [
            'appid' => $this->clientId, 'secret' => $this->clientSecret,
            'code' => $code, 'grant_type' => 'authorization_code',
        ];
    }

    /**
     * {@inheritdoc}.
     */
    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody()->getContents());
    }

    /**
     * {@inheritdoc}.
     */
    protected function parseAccessToken($body)
    {
        $jsonArray = json_decode($body, true);

        if (isset($jsonArray['errcode'])) {
            throw new \Exception($jsonArray['errmsg'], $jsonArray['errcode']);
        }

        $this->openId = $jsonArray['openid'];

        return $jsonArray['access_token'];
    }

    /**
     * @param mixed $response
     *
     * @return string
     */
    protected function removeCallback($response)
    {
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }

        return $response;
    }

    /**
     * Get the code from the request.
     *
     * @return string
     */
    protected function getCode()
    {
        return \Request::input('code');
    }


}
