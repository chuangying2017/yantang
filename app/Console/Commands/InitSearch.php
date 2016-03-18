<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use XS;
use XSDocument;

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
        $xs = new XS(env('SEARCH_APP_NAME'));
        $this->index = $xs->index;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->index->beginRebuild();
        $products = Product::with('meta')->get();
        foreach ($products as $product) {
            $data = array(
                'id'     => $product['id'], // 此字段为主键，必须指定
                'title'  => $product['title'],
                'tags'   => array_get($product, 'meta.tags'),
                'detail' => array_get($product, 'meta.detail')
            );

            // 创建文档对象
            $doc = new XSDocument;
            $doc->setFields($data);

            // 添加到索引数据库中
            $this->index->add($doc);
        }
        $this->index->endRebuild();
    }
}
