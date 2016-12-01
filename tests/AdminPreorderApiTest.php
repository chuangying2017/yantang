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
                'export' => 'all',
                'time_name' => 'confirm_at',
                'start_time' => '2016-08-27',
                'end_time' => '2016-10-31',
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
        $preorder_id = 11694;
        $station_id = 47;
        $normal_station_admin = 10542;

        $invoice_preorder = \App\Models\Subscribe\Preorder::query()->where('status', \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING)
            ->where('invoice', 1)->first();
        $un_invoice_preorder = \App\Models\Subscribe\Preorder::query()->where('status', \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING)
            ->where('invoice', 0)->first();


        $this->json('put', 'admin/subscribe/orders/' . $un_invoice_preorder['id'],
            [
                'station' => $station_id
            ],
            $this->getAuthHeader(1));


        $this->assertResponseStatus(200);

        $un_invoice_preorder = \App\Models\Subscribe\Preorder::query()->find($un_invoice_preorder['id']);

        $this->assertNull($un_invoice_preorder['confirm_at']);

        $this->seeInDatabase('preorders', ['id' => $un_invoice_preorder['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_ASSIGNING]);
        $this->seeInDatabase('preorder_assign', ['preorder_id' => $un_invoice_preorder['id'], 'station_id' => $station_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED, 'memo' => '接错重派']);


        $this->json('put', 'admin/subscribe/orders/' . $un_invoice_preorder['id'],
            [
                'station' => $station_id
            ],
            $this->getAuthHeader(1));

        $invoice_preorder = \App\Models\Subscribe\Preorder::query()->find($invoice_preorder['id']);
        $this->assertNotNull($invoice_preorder['confirm_at']);


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

