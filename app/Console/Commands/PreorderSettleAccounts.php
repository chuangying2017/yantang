<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Subscribe\PreorderService;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PreorderSettleAccounts extends Command
{
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
        PreorderService::settle();
    }
}