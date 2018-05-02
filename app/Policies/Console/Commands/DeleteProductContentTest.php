<?php

namespace App\Console\Commands;

use App\Models\Product\ProductMeta;
use Illuminate\Console\Command;

class DeleteProductContentTest extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:content-clear';

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
        \DB::table('product_meta')->chunk(50, function ($data) {
            foreach ($data as $value) {
                if (strpos($value->detail, "test") !== false) {
                    \DB::table('product_meta')->where('id', $value->id)->update(['detail' => str_replace("test</p>", "</p>", $value->detail)]);
                }
            }
        });
    }
}
