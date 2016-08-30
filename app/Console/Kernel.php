<?php namespace App\Console;


use App\Services\Statement\StatementProtocol;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\StoreSettleStatement',
        'App\Console\Commands\StationSettleStatement',
        'App\Console\Commands\SettlePreorder',
        'App\Console\Commands\InitSearch',
        'App\Console\Commands\CleanData',
        'App\Console\Commands\SeedCouponForFirstOrderUser',
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
        $schedule->command('statement:store')->monthlyOn(StatementProtocol::getStoreCheckDay(), '1:00');
        $schedule->command('statement:station')->monthlyOn(StatementProtocol::getStationCheckDay(), '2:00');
    }
}
