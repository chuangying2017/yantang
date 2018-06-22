<?php

namespace App\Console\Commands;

use App\Services\Statement\StoreStatementService;
use Illuminate\Console\Command;

class StoreSettleStatement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statement:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var StoreStatementService
     */
    private $statementService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StoreStatementService $statementService)
    {
        parent::__construct();
        $this->statementService = $statementService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->statementService->generateStatements();
    }
}
