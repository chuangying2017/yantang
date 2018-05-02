<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use App\Models\Order\OrderMark;
use App\Repositories\Order\Mark\OrderMarkRepo;
use App\Services\Order\OrderManageService;
use App\Services\Order\OrderProtocol;
use Illuminate\Console\Command;

class AddMarkToOrder extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:add-mark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param OrderManageService $orderManageService
     */
    public function __construct(OrderManageService $orderManageService)
    {
        parent::__construct();
        $this->orderManageService = $orderManageService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get recent order mark
        $order_id = OrderMark::orderBy('order_id', 'desc')->pluck('order_id')->first();
        if ($order_id) {
            $orders = Order::query()->paid()->where('id', '>', $order_id)->get();
        } else {
            $orders = Order::query()->paid()->get();
        }

        foreach ($orders as $order) {
            if ($this->orderManageService->orderIsFirstPaid($order)) {
                OrderMarkRepo::addMark($order['id'], OrderProtocol::ORDER_MARK_TYPE_OF_FIRST, OrderProtocol::ORDER_MARK_CONTENT_OF_FIRST);
                echo $order['id'] . ' is ' . ' mark' . "\n";
            }
        }
    }

    /**
     * @var OrderManageService
     */
    private $orderManageService;
}
