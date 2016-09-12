<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use Illuminate\Console\Command;

class OrderFillTimeData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:fill-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '填充订单时间数据';

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
        Order::with('billings')->where('pay_status', 'paid')->where('refund_status', 'none')->whereNull('pay_at')->chunk(1000, function ($orders) {
            foreach ($orders as $order) {
                $this->count++;
                $order->pay_at = $order->billings->first()->updated_at;
                $order->save();
            }
        });

        echo "change " . $this->count;
    }

    protected $count = 0;
}
