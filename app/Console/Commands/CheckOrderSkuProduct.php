<?php

namespace App\Console\Commands;

use App\Models\Order\OrderSku;
use Illuminate\Console\Command;

class CheckOrderSkuProduct extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-sku-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $orders = OrderSku::withTrashed()->with(['product' => function ($query) {
            $query->withTrashed();
        }])->get();
        foreach ($orders as $order) {
            $order['product_id'] = $order['product']['product_id'];
            $order->save();
            echo "update " . $order['id'] . "\n";
        }

        echo "done";
    }
}
