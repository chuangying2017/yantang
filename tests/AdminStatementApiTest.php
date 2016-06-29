<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStatementApiTest extends TestCase {

    /** @test */
    public function it_can_get_admin_statements_lists()
    {
        $this->json('get', 'admin/statements/store', [], $this->getAuthHeader());

        $this->seeJsonStructure(['data' => [['statement_no']]]);
    }
}
