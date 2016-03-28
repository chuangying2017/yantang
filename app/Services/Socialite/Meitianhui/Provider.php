<?php namespace App\Services\Socialite\Meitianhui;

use Illuminate\Support\Str;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;


class Provider extends AbstractProvider implements ProviderInterface {

    const FAIL_CODE = 'fail';
    const SUCCESS_CODE = 'succ';

    /**
     * {@inheritdoc}.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('http://t-openapi.meitianhui.com/oauth/show', $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $session = $this->request->getSession();

        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);

        return $url . '?' . $query;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return [
            'appid'         => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'response_type' => 'code',
            'state'         => $state,
        ];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenUrl()
    {
        return 'http://t-openapi.meitianhui.com/oauth/token';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getUserByToken($result)
    {
        return $result['data'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['mobile'],
            'phone'    => $user['mobile'],
            'union_id' => array_get($user, 'unionid', null),
            'nickname' => array_get($user, 'nick_name', '每天惠用户' . Str::random(5)),
            'avatar'   => array_get($user, 'headimgurl', 'http://7xpdx2.com2.z0.glb.qiniucdn.com/default.jpeg'),
            'name'     => array_get($user, 'full_name', null),
            'email'    => null,
        ]);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenFields($code)
    {
        return [
            'appid'        => $this->clientId,
            'private_key'  => $this->clientSecret,
            'code'         => $code,
            'grant_type'   => 'authorization_code',
            'redirect_uri' => $this->redirectUrl,
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

        if ($jsonArray['rsp_code'] == self::FAIL_CODE) {
            throw new \Exception($jsonArray['error_msg'], 401);
        }

        return $jsonArray;
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
}
