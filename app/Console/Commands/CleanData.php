<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use App\Models\Order\OrderSku;
use Illuminate\Console\Command;

class CleanData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除订单数据';

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
        if ($this->confirm('确定删除数据吗? [y|N]')) {

            \DB::beginTransaction();

            \DB::table('charge_billings')->delete();
            \DB::table('child_orders')->delete();
            \DB::table('deliver_preorder_skus')->delete();
            \DB::table('order_address')->delete();
            \DB::table('order_billing')->delete();
            \DB::table('order_deliver')->delete();
            \DB::table('order_memo')->delete();
            \DB::table('order_product_review')->delete();
            \DB::table('order_promotions')->delete();
            \DB::table('order_review')->delete();
            \DB::table('order_skus')->delete();
            \DB::table('order_special_campaign')->delete();
            \DB::table('order_tickets')->delete();
            \DB::table('orders')->delete();
            \DB::table('return_orders')->delete();
//            \DB::table('pingxx_payments')->delete();
//            \DB::table('pingxx_refund')->delete();
//            \DB::table('pingxx_transfer')->delete();
            \DB::table('preorder_assign')->delete();
            \DB::table('preorder_deliver')->delete();
            \DB::table('preorder_assign')->delete();
            \DB::table('preorder_pause')->delete();
            \DB::table('preorder_skus')->delete();
            \DB::table('preorders')->delete();
            \DB::table('addresses')->delete();
            \DB::table('address_info')->delete();
            \DB::table('statement_products')->delete();
            \DB::table('statements')->delete();
            \DB::table('tickets')->delete();
            \DB::table('wallet_records')->delete();
            \DB::table('credits_records')->delete();

            if ($this->confirm('真的要删除数据吗? [y|N]')) {
                \DB::commit();
                echo "已删除";
            } else {
                echo "已取消";
            }
        }
    }
}
