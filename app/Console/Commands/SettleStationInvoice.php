<?php

namespace App\Console\Commands;

use App\Services\Invoice\StationInvoiceService;
use Illuminate\Console\Command;

class SettleStationInvoice extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:station {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成账单';

    /**
     * Create a new command instance.
     *
     * @param StationInvoiceService $stationInvoiceService
     */
    public function __construct(StationInvoiceService $stationInvoiceService)
    {
        parent::__construct();
        $this->stationInvoiceService = $stationInvoiceService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->stationInvoiceService->settleAll($this->argument('date'));
    }

    /**
     * @var StationInvoiceService
     */
    private $stationInvoiceService;
}
