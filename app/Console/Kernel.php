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
        'App\Console\Commands\SeedCouponForFirstOrderUser',
        'App\Console\Commands\OrderFillTimeData',
        'App\Console\Commands\PreorderFillTimeData',
        'App\Console\Commands\SettleStationInvoice',
        'App\Console\Commands\CancelOvertimeUnpaidOrders',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('preorder:settle')->dailyAt('1:00');
        $schedule->command('preorders:assign')->twiceDaily(10, 16);
//        $schedule->command('statement:store')->monthlyOn(StatementProtocol::getStoreCheckDay(), '1:00');
//        $schedule->command('statement:station')->monthlyOn(StatementProtocol::getStationCheckDay(), '2:00');
//

//        $schedule->command('invoice:station', ['date' => Carbon::today()->day(10)->toDateString()])->monthlyOn(11, '2:00');
//        $schedule->command('invoice:station', ['date' => Carbon::today()->day(25)->toDateString()])->monthlyOn(26, '2:00');

        $schedule->command('orders:overtime')->everyTenMinutes();

    }
}
