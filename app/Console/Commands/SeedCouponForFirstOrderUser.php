<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Order\OrderProtocol;
use App\Services\Promotion\CouponService;
use Illuminate\Console\Command;
use Storage;

class SeedCouponForFirstOrderUser extends Command {

    const SEED_COUPON_USER_FILE_NAME = '_coupon_seed_user.json';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:seed {coupon_id} {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'example => coupon:seed 9 | coupon:seed reset,9';

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
        if (strpos($coupon_id, 'reset') !== false) {
            $this->initSeedUser(explode(',', $coupon_id)[1]);
            echo 'reset';
            return;
        }

        $user_ids = $this->option('user');
        if ($user_ids) {
            $count = $this->couponService->dispatchWithoutCheck($user_ids, $coupon_id);
            echo 'success, seed: ' . $count;

            return;
        }

        $user_ids = $this->getFirstPaidOrderUserIds($coupon_id);

        $count = $this->couponService->dispatchWithoutCheck($user_ids, $coupon_id);

        $this->storeSeedUser($coupon_id, $user_ids);

        echo 'success, seed: ' . $count;
    }


    private function getFirstPaidOrderUserIds($coupon_id)
    {
        $from_database_user_ids = array_unique(
            Order::query()->where('pay_status', OrderProtocol::PAID_STATUS_OF_PAID)
                ->where('refund_status', OrderProtocol::REFUND_STATUS_OF_DEFAULT)
                ->where('order_type', OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE)
                ->pluck('user_id')->all()
        );

        return array_diff($from_database_user_ids, $this->getSeedUser($coupon_id));
    }

    private function storeSeedUser($coupon_id, $user_ids)
    {
        $new_user_ids = array_unique(
            array_merge(
                $user_ids,
                $this->getSeedUser($coupon_id)
            )
        );
        Storage::disk('local')->put($coupon_id . self::SEED_COUPON_USER_FILE_NAME, json_encode($new_user_ids));
    }

    private function getSeedUser($coupon_id)
    {
        if (!Storage::disk('local')->exists($coupon_id . self::SEED_COUPON_USER_FILE_NAME)) {
            $this->initSeedUser($coupon_id);
        }
        return json_decode(Storage::disk('local')->get($coupon_id . self::SEED_COUPON_USER_FILE_NAME), true);
    }

    private function initSeedUser($coupon_id)
    {
        Storage::disk('local')->put($coupon_id . self::SEED_COUPON_USER_FILE_NAME, json_encode([]));
    }

    /**
     * @var CouponService
     */
    private $couponService;


}
