<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserThirdPartyAuthTest extends TestCase {

    use DatabaseTransactions;

    protected $provider;
    protected $oauth_code;


    /** @test */
    public function it_get_weixin_oauth_redirect_url()
    {
        $this->setProvider('weixin');
        $this->oauthUrl();
        $this->dumpResponse();
    }

    /** @test */
    public function user_can_register_by_weixin_oauth()
    {
        $this->setProvider('weixin');

        // need setup
        $this->oauth_code = '123';

        $this->oathRegister();
    }

    public function oathRegister()
    {
        $request_data = [
            'code' => $this->oauth_code
        ];

        $this->json('post', '/auth/login/' . $this->provider, $request_data);
        $this->dumpResponse();


        $this->seeJsonStructure(['data' => ['token', 'roles' => []]]);

    }

    protected function oauthUrl()
    {

        $this->json('get', '/auth/login/' . $this->provider);

        $this->seeJsonStructure(['data' => ['url']]);

        $this->assertResponseOk();
    }

    protected function setProvider($provider)
    {
        $this->provider = $provider;
    }

}
