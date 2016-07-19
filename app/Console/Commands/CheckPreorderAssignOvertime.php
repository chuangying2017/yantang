<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPreorderAssignOvertime extends Command {

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
        
    }
}
