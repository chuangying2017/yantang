<?php

namespace App\Console\Commands;

use App\Models\Integral\SignMonthModel;
use App\Repositories\Integral\SignHandle\SignVerifyClass;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ContinueSign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'continue_sing:clear';

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
    protected $signClass;

    public function __construct(SignVerifyClass $signVerifyClass)
    {
        parent::__construct();

        $this->signClass = $signVerifyClass;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $signModel = SignMonthModel::query()->whereYear('created_at','=',Carbon::now()->year)->whereMonth('created_at','=',Carbon::now()->month)->get();

        foreach ($signModel as $key => $value)
        {
            $day = Carbon::now()->day;

            $count = $this->signClass->verifyDay($value,$day);

            if ($count)
            {
                continue;
            }

            $yestoday = $this->signClass->verifyDay($value,$day - 1);

            if (!$yestoday)
            {
                $value->continuousSign = 0;

                $value->save();
            }
        }

    }
}
