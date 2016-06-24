<?php

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
        var_dump($this->getResponseData());
    }

    protected function getResponseData($key = null)
    {
        $content = json_decode($this->response->getContent(), true);

        if ($key) {
            return array_get($content, 'data');
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


}
