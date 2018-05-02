<?php

namespace App\Console\Commands;

use App\Models\AgentOrder;
use App\Services\Agent\AgentRepository;
use Illuminate\Console\Command;

class CheckAgentOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:agentOrder {order_id}';

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
        event(new \App\Services\Agent\Event\NewAgentOrder(AgentRepository::getAgentOrderById($this->argument('order_id'))));
    }
}
