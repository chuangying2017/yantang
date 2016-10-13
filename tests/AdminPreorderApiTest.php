<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminPreorderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_all_preorders()
    {
        $this->json('get', 'admin/subscribe/orders',
            [
                'station_id' => '4'
            ],
            $this->getAuthHeader());

        $this->dump();
    }

    /** @test */
    public function it_can_export_all_preorders()
    {
        $this->json('get', 'admin/subscribe/orders',
            [
                'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_ASSIGNING,
                'export' => 'all'
            ],
            $this->getAuthHeader());
        $this->dump();
    }

    /** @test */
    public function it_can_show_a_preorder_detail()
    {
        $this->json('get', 'admin/subscribe/orders/' . 15,
            [],
            $this->getAuthHeader());

        $this->echoJson();

    }

    /** @test */
    public function it_can_assign_new_station_for_preorder()
    {
        $preorder_id = 15;
        $station_id = 2;

        $this->json('put', 'admin/subscribe/orders/' . $preorder_id,
            [
                'station' => $station_id
            ],
            $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(200);

        $this->seeInDatabase('preorder_assign', ['preorder_id' => $preorder_id, 'station_id' => $station_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED]);
    }

    /** @test */
    public function it_can_get_all_not_handle_on_time_preorders()
    {
        $this->json('get', 'admin/subscribe/orders',
            [
                'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_OVERTIME
            ],
            $this->getAuthHeader());

        $this->dump();

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_get_all_cancel_overtime_order()
    {
        $api = app('Dingo\Api\Dispatcher');

        $admin_user_id = 1;
        $user = \App\Models\Access\User\User::find($admin_user_id);
        $jwt_token = JWTAuth::fromUser($user);

        $api->header('Authorization', 'Bearer ' . $jwt_token);

        $orders = $api->get('api/admin/subscribe/origin-orders', [
            'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_UNPAID,
            'per_page' => 20,
            'order_by' => 'created_at',
            'sort' => 'desc',
            'page' => 1
        ]);

        $orders = $orders->toArray();

        dd($orders);

        $order = array_first($orders['data']);




        $api->delete('api/admin/subscribe/origin-orders/' . $order['id']);



        $orders = $api->get('api/admin/subscribe/origin-orders', [
            'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_UNPAID,
            'per_page' => 20,
            'order_by' => 'created_at',
            'sort' => 'desc',
            'page' => 1
        ]);

        $orders = $orders->toArray();

        $order = array_first($orders['data']);

    }
}

