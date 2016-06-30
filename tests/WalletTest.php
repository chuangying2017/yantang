<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase
{
    /** @test */
    public function it_can_get_user_wallet_records()
    {
        $this->json('get', 'users/wallets', [], $this->getAuthHeader());

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_wallet_amount()
    {
        $this->json('get', 'users/wallets/balance', [], $this->getAuthHeader());

        $this->dump();
    }
}
