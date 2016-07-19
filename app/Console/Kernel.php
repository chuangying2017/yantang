<?php namespace App\Console;


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
    }
}
