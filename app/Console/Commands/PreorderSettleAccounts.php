<?php

namespace App\Console\Commands;

use App\Services\Preorder\PreorderSettleServiceContract;
use Illuminate\Console\Command;
use App\Services\Preorder\PreorderSettleService;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PreorderSettleAccounts extends Command {

    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:settle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'preorder settle accounts by everyday';
    /**
     * @var PreorderSettleServiceContract
     */
    private $settleService;


    /**
     * PreorderSettleAccounts constructor.
     * @param PreorderSettleServiceContract $settleService
     */
    public function __construct(PreorderSettleServiceContract $settleService)
    {
        parent::__construct();
        $this->settleService = $settleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->settleService->settle();
    }
}
