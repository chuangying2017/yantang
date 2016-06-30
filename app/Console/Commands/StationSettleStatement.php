<?php

namespace App\Console\Commands;

use App\Services\Statement\StationStatementService;
use Illuminate\Console\Command;

class StationSettleStatement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statement:station';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var StationStatementService
     */
    private $statementService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StationStatementService $statementService)
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
