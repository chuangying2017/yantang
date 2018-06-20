<?php namespace App\Console;


use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        'App\Console\Commands\StoreSettleStatement',
//        'App\Console\Commands\StationSettleStatement',
        'App\Console\Commands\SettlePreorder',
        'App\Console\Commands\InitSearch',
//        'App\Console\Commands\CleanData',
        'App\Console\Commands\CheckPreorderAssignOvertime',
        'App\Console\Commands\RemoveDuplicatePreorderDeliver',
//        'App\Console\Commands\SeedCouponForFirstOrderUser',
        'App\Console\Commands\OrderFillTimeData',
        'App\Console\Commands\PreorderFillTimeData',
        'App\Console\Commands\SettleStationInvoice',
        'App\Console\Commands\InvoiceCheck',
        'App\Console\Commands\CancelOvertimeUnpaidOrders',

        //counter
        'App\Console\Commands\FillPreorderAmount',
        'App\Console\Commands\InitStationCounter',
        'App\Console\Commands\DailyStationCounter',

        //订单处理
        'App\Console\Commands\AddMarkToOrder',

        //提醒
        'App\Console\Commands\NotifyClientIfPreorderIsEnding',
        'App\Console\Commands\NotifyClientIfTicketIsEnding',

        //client
        'App\Console\Commands\FillClientGender',

        //coupon
        'App\Console\Commands\SeedCouponForSpecifyUser',
        
        //A single delivery and Multiple delivery (alert)
        'App\Console\Commands\NotifyClientCommentAlert',
        

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //标记发货
        $preorder_settle_start_time = env('PREORDER_SETTLE_START_TIME', '2017-02-08 00:00:00');
        if(Carbon::parse($preorder_settle_start_time) < Carbon::now()) {
            $schedule->command('preorder:settle')->dailyAt('1:00');
        }

        //发送超时提醒信息
        $schedule->command('preorders:assign')->twiceDaily(10, 16);

        //计数器
        $schedule->command('counter:daily-station')->dailyAt('00:30');

        //账单
       $schedule->command('invoice:station', [Carbon::today()->day(10)->toDateString()])->monthlyOn(11, '2:00');
       $schedule->command('invoice:station', [Carbon::today()->day(25)->toDateString()])->monthlyOn(26, '2:00');

        $schedule->command('orders:overtime')->everyTenMinutes();
        
        //A single delivery And Multiple delivery
        $schedule->command('notify:client-comment-alert')->dailyAt('8:01');
        //dailyAt('8:43');
        
        $schedule->command('notify:preorder-ending')->dailyAt('8:00');
        
    }
}
