<?php

namespace App\Console\Commands;

use App\Events\Preorder\PreordersNotHandleInTime;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Illuminate\Console\Command;

class CheckPreorderAssignOvertime extends Command {

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorders:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查订奶订单是否未按时处理';

    /**
     * Create a new command instance.
     *
     * @param PreorderRepositoryContract $preorderRepo
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        parent::__construct();
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = $this->preorderRepo->getAllNotAssignOnTime();
        if ($orders->first()) {
            event(new PreordersNotHandleInTime());
        }
    }


}
