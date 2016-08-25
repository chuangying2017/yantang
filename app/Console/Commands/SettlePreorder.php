<?php

namespace App\Console\Commands;

use App\Services\Preorder\PreorderSettleService;
use Illuminate\Console\Command;

class SettlePreorder extends Command
{
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
    protected $description = '结算订奶订单';
    /**
     * @var PreorderSettleService
     */
    private $settleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PreorderSettleService $settleService)
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
