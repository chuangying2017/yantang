<?php

use App\Models\Subscribe\Station;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://yt.vg/api';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        return $app;
    }

    protected function dumpResponse()
    {
        print_r($this->getResponseData());
    }

    public function echoJson()
    {
        echo $this->response->getContent();
    }

    protected function getResponseData($key = null)
    {
        $content = json_decode($this->response->getContent(), true);

        if ($key) {
            return array_get($content, $key);
        }
        return $content;
    }

    protected function getFaker()
    {
        return Faker\Factory::create();
    }

    protected function setUser($user_id = null)
    {
        if ($user_id) {
            $user = \App\Models\Access\User\User::find($user_id);
        } else {
            $user = \App\Models\Access\User\User::create();
        }

        $this->actingAs($user);
        return $user;
    }

    protected function getToken($user_id = null)
    {
        $user = $this->setUser($user_id);

        return JWTAuth::fromUser($user);

    }

    public function getUrl($url)
    {
        //模拟付款
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $content;
    }

    public function getAuthHeader($user_id = 1)
    {
        return ['Authorization' => 'Bearer ' . $this->getToken($user_id)];
    }



}
