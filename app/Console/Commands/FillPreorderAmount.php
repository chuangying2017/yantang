<?php

namespace App\Console\Commands;

use App\Models\Subscribe\Preorder;
use Illuminate\Console\Command;

class FillPreorderAmount extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:fill-amount';

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
        Preorder::with([
            'order',
        ])->chunk(1000, function ($preorders) {
            foreach ($preorders as $preorder) {
                if ($preorder['total_amount'] == 0) {
                    $this->updateAmount($preorder);
                    $this->count++;
                }
            }
        });

        echo 'total ' . $this->count;
    }

    protected function updateAmount($preorder)
    {
        $preorder['total_amount'] = $preorder['order']['total_amount'];
        echo "fill " . $preorder['id'] . ' as ' . $preorder['total_amount'] . "\n";
        $preorder->save();
    }

    protected $count = 0;

}
