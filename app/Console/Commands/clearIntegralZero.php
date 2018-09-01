<?php

namespace App\Console\Commands;

use App\Models\Client\Account\Wallet;
use App\Repositories\setting\SetMode;
use Carbon\Carbon;
use Illuminate\Console\Command;

class clearIntegralZero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:integralAll';

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
    protected $setMode;
    public function __construct(SetMode $setMode)
    {
        parent::__construct();
        $this->setMode = $setMode;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $setting = $this->setMode->getSetting(3);

        if (!isset($setting->value['date']) && empty($setting->value['date'])){
            return false;
        }

        if (Carbon::now()->toDateString() == $setting->value['date'])
        {
            file_put_contents('clear.txt',$setting->value['date']);
        Wallet::query()->update(['integral' => 0]);
        $setting->value = ['year' => $setting->value['year'],'date' => Carbon::now()->addYear($setting->value['year'])->toDateString()];
        $setting->save();
        }
    }
}
