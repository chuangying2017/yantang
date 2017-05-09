<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Dingo\Api\Dispatcher;
use Illuminate\Console\Command;
use JWTAuth;

class CancelOvertimeUnpaidOrders extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:overtime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cancel overtime unpaid orders';

    /**
     * Create a new command instance.
     * @param Dispatcher $api
     */
    public function __construct(Dispatcher $api)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->api = $api;

        $admin_user_id = 1;
        $user = \App\Models\Access\User\User::find($admin_user_id);
        $jwt_token = JWTAuth::fromUser($user);

        $this->api->header('Authorization', 'Bearer ' . $jwt_token);

        $this->request_data = [
            'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_UNPAID,
            'per_page' => 20,
            'order_by' => 'created_at',
            'sort' => 'asc',
            'page' => 1
        ];

        $pay_time_before = Carbon::now()->subHours(2);
        $count = 0;

        try {
            while (1) {
                $orders = $this->getOrders();

                $this->getOrders();

                echo "prepare to cancel count: " . count($orders) . "\n";

                if (!count($orders)) {
                    die('nothing to cancel!');
                }

                foreach ($orders as $order) {
                    if ($order['created_at'] < $pay_time_before) {
                        $this->api->delete('api/admin/subscribe/origin-orders/' . $order['id']);
                        $count++;
                        echo 'cancel ' . $order['id'] . "\n";
                    } else {
                        //未到取消时间
                        echo('can not cancel order ' . $order['id'] . ' :' . $order['created_at']);
                        die('total cancel ' . $count);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    protected function getOrders($page = 1, $all = false)
    {
        $this->request_data['page'] = $page;
        $orders = $this->api->get('api/admin/subscribe/origin-orders', $this->request_data);

        $orders = $orders->toArray();
        if ($all) {
            return $orders;
        }

        return $orders['data'];
    }

    /**
     * @var Dispatcher
     */
    private $api;
    protected $request_data;

}
