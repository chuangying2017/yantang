<?php

namespace App\Console\Commands;

use App\Models\ChildOrder;
use App\Services\Orders\OrderManager;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetOrderDoneIfOverTime extends Command {

    const OVER_TIME_DAYS = 15;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查询并完成逾期未确认的已发货订单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
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
        //处理旧数据,发货日期填充为最后更新时间
//        \DB::update("UPDATE `child_orders` a
//            JOIN `order_deliver` b ON a.deliver_id = b.id
//            SET a.`deliver_at` = b.`created_at`");

        $total = ChildOrder::where('status', OrderProtocol::STATUS_OF_DELIVER)
            ->where('deliver_at', '<', Carbon::now()->subDay(self::OVER_TIME_DAYS))
            ->update(['status' => OrderProtocol::STATUS_OF_DONE]);

        echo " {$total} 个订单逾期自动完成 \n";
        info(" {$total} 个订单逾期自动完成 \n");
    }
}
