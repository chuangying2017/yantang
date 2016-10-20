<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OpenApiWeixinTest extends TestCase {

    /** @test */
    public function it_can_get_weixin_token()
    {
        $this->json('get', 'open/weixin/token', []);
        $this->dump();
    }
}
