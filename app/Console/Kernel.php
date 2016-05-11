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
        'App\Console\Commands\Inspire',
        'App\Console\Commands\CheckAgentOrder',
        'App\Console\Commands\InitSearch',
        'App\Console\Commands\DeleteProductContentTest',
        'App\Console\Commands\CheckOrderSkuProduct',
        'App\Console\Commands\SetOrderDoneIfOverTime',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('orders:done')->everyFiveMinutes();
    }
}
