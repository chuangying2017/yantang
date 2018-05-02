<?php

namespace App\Console\Commands;

use App\Models\Subscribe\PreorderDeliver;
use Illuminate\Console\Command;

class RemoveDuplicatePreorderDeliver extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:remove-deliver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除重复发货信息';

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
        $delivers = $this->getDuplicatePreorderDeliver();
        $delivers->load('skus');
        foreach ($delivers as $deliver) {
            foreach ($deliver['skus'] as $sku) {
                //回复remain数量
                $sku->increment('remain', ($deliver['count'] - 1) * $sku['per_day']);
            }
            //删除deliver&sku
            $deliver->skus()->detach();
            $deliver->delete();
        }
        echo "删除" . count($delivers);
    }

    protected function getDuplicatePreorderDeliver()
    {
        return PreorderDeliver::query()->select('id', 'preorder_id', 'deliver_at', \DB::raw('COUNT(*) as count'))->groupBy('deliver_at', 'preorder_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();
    }
}
