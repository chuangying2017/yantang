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

        if (empty($signModel))
        {
            return false;
        }

        foreach ($signModel as $key => $value)
        {
            $day = Carbon::now()->day;

            $count = $this->monthModel($value,$day);

            if ($count)
            {
                continue;
            }

            $yesterday = $this->monthModel($value,$day - 1);

            if (!$yesterday)
            {
                $value->continuousSign = 0;

                $value->save();
            }
        }

    }

    public function monthModel($model,$day)
    {
       $arr = $model->signArray;

       if (in_array($day,$arr))
       {
           return true;
       }

       return false;
    }
}
