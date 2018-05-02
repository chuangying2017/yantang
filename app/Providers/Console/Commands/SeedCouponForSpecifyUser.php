<?php

namespace App\Console\Commands;

use App\Models\Client\Client;
use App\Models\Order\Order;
use App\Models\Subscribe\Preorder;
use App\Services\Promotion\CouponService;
use Illuminate\Console\Command;

class SeedCouponForSpecifyUser extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:seed-user {coupon_id} {--user=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'type: all,order';

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * Create a new command instance.
     *
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        parent::__construct();
        $this->couponService = $couponService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coupon_id = $this->argument('coupon_id');

        $type = $this->option('user');

        if ($type == 'order') {
            Order::query()
                ->select('user_id', 'pay_status', 'paid', 'refund_status', 'order_type', 'id')
                ->where('pay_status', 'paid')
                ->where('refund_status', 'none')
                ->where('order_type', 4)
                ->groupBy('user_id')
                ->chunk(1000, function ($orders) use ($coupon_id) {
                    $user_ids = $orders->pluck('user_id')->all();
                    $this->seedCoupon($user_ids, $coupon_id);
                });
        }

        if ($type = 'all') {
            Client::query()
                ->select('user_id')
                ->chunk(1000, function ($clients) use ($coupon_id) {
                    $user_ids = $clients->pluck('user_id')->all();
                    $this->seedCoupon($user_ids, $coupon_id);
                });
        }
    }

    function seedCoupon($user_ids, $coupon_id)
    {
        $count = $this->couponService->dispatchWithoutCheck($user_ids, $coupon_id);
        $this->count += $count;
        echo 'success, seed: ' . $this->count . "\n";
    }

    public $count = 0;
}
