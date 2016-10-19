<?php

namespace App\Console\Commands;

use App\Models\Subscribe\Preorder;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Notify\NotifyProtocol;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyClientIfPreorderIsEnding extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:ending-notify {days=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $preorders = $this->preorderRepo->getAllEnding($this->argument('days'));
        foreach ($preorders as $preorder) {
            NotifyProtocol::notify($preorder['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_PREORDER_IS_ENDING, null, $preorder);
        }
    }

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;
}
