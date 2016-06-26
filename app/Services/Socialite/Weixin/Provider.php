<?php

namespace App\Services\Socialite\Weixin;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'WEIXIN';

    /**
     * @var string
     */
    protected $openId;

    /**
     * {@inheritdoc}.
     */
    protected $scopes = ['snsapi_login'];

    /**
     * set Open Id.
     *
     * @param string $openId
     */
    public function setOpenId($openId)
    {
        $this->openId = $openId;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getConfig(
            'auth_base_uri',
            'https://open.weixin.qq.com/connect/oauth2/authorize'
        ), $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);

        return $url.'?'.$query.'#wechat_redirect';
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
            'state' => $state,
        ];
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
            'nickname' => $user['nickname'],
            'union_id' => array_get($user, 'unionid', null),
            'avatar' => $user['headimgurl'],
            'name' => null, 'email' => null,
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
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        $this->credentialsResponseBody = json_decode($response->getBody(), true);

        if (isset($this->credentialsResponseBody['errcode'])) {
            throw new \Exception($this->credentialsResponseBody['errmsg'], $this->credentialsResponseBody['errcode']);
        }


        $this->openId = $this->credentialsResponseBody['openid'];

        return $this->credentialsResponseBody;
    }

    public static function additionalConfigKeys()
    {
        return ['auth_base_uri'];
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
