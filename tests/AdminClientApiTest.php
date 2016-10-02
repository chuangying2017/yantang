<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminClientApiTest extends TestCase
{
    /** @test */
    public function it_can_get_all_clients()
    {
        $this->json('get', 'admin/clients/users', [
            'keyword' => '曹',
        ], $this->getAuthHeader());

        $this->dump();

        $this->assertResponseOk();
    }
}
