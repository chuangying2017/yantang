<?php

use App\Services\Notify\NotifyProtocol;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotifyServiceTest extends TestCase {

    /** @test */
    public function it_can_send_weixin_message_to_station_owner()
    {
        $preorder_id = 823;
        $preorder = app(\App\Repositories\Preorder\PreorderRepositoryContract::class)->get($preorder_id);;
        NotifyProtocol::notify($preorder['station_id'], NotifyProtocol::NOTIFY_ACTION_STATION_NEW_ORDER, null, $preorder);
    }
}
