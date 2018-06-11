<?php

namespace App\Console\Commands;

use App\Models\Subscribe\Preorder;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyClientCommentAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:client-comment-alert';

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
        // dispose
        Preorder::query()
            ->where('status',PreorderProtocol::ORDER_STATUS_OF_SHIPPING)
            ->chunk('100',function ($collectData){
                foreach ($collectData as $collectDatum){

                }
            });
    }
}
