<?php

namespace App\Console\Commands;

use App\Models\Subscribe\Preorder;
use Illuminate\Console\Command;

class PreorderFillTimeData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:fill-time';

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
//        $preorder = (Preorder::with(['deliver' => function ($query) {
//            $query->orderBy('deliver_at', 'asc')->limit(1);
//        }])->find(768));
//


        Preorder::with([
            'order',
        ])->whereIn('status', ['assigning', 'shipping', 'done'])->whereNull('pay_at')->chunk(100, function ($preorders) {
            foreach ($preorders as $preorder) {
                $this->updatePayTime($preorder);
                $this->count++;
            }
        });


//        Preorder::with([
//            'assign',
//            'order',
//            'deliver' => function ($query) {
//                $query->orderBy('deliver_at', 'asc');
//            }
//        ])->whereIn('status', ['assigning'])->chunk(100, function ($preorders) {
//            foreach ($preorders as $preorder) {
//                $this->updateTimes($preorder);
//            }
//        });

//        Preorder::with([
//            'assign',
//            'order',
//            'deliver' => function ($query) {
//                $query->orderBy('deliver_at', 'asc');
//            }
//        ])->whereIn('status', ['shipping', 'done'])->chunk(100, function ($preorders) {
//            foreach ($preorders as $preorder) {
//                $this->updateTimes($preorder);
//            }
//        });

        echo 'change ' . $this->count;
    }

    protected function updateTimes($preorder)
    {
        $preorder->pay_at = $preorder->order->pay_at;

        $preorder->confirm_at = $preorder->assign->confirm_at;

        $this->count++;

        $deliver = $preorder->deliver->first();
        if ($deliver) {
            $preorder->deliver_at = $deliver->deliver_at;
        }

        $preorder->save();
    }

    protected function updatePayTime($preorder)
    {
        $preorder->pay_at = $preorder->order->pay_at;

        $preorder->save();
    }



    protected $count = 0;
}
