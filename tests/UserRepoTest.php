<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRepoTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_users_by_roles()
    {
        $users = app()->make(\App\Repositories\Backend\User\UserContract::class)->getAllUsersByRole(\App\Repositories\Backend\AccessProtocol::ROLE_OF_STATION_ADMIN);

        $this->assertNotNull($users->first());
    }
}
