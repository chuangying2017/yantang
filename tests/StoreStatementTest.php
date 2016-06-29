<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreStatementTest extends TestCase
{
    /** @test */
    public function it_can_get_store_statements()
    {
        $this->json('get', 'store/statements', [], $this->getAuthHeader());



    }
}
