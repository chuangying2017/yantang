<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Repositories\Search\Item\ProductSearchRepository;
use App\Services\Product\Search\Facades\ProductSearch;
use Illuminate\Console\Command;

class InitSearch extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init XunSearch';

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
        app()->make(ProductSearchRepository::class)->init();
    }
}
