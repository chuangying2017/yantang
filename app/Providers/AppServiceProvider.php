<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use XS;
use XSDocument;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        define('XS_APP_ROOT', env('SEARCH_INI_PATH'));

        Product::saved(function ($product) {
            $product->load('meta');

            $data = array(
                'id'     => $product['id'], // 此字段为主键，必须指定
                'title'  => $product['title'],
                'tags'   => array_get($product, 'meta.tags'),
                'detail' => array_get($product, 'meta.detail')
            );

            // 创建文档对象
            $doc = new XSDocument;
            $doc->setFields($data);
            $xs = new XS(env('SEARCH_APP_NAME'));
            $index = $xs->index;
            // 添加到索引数据库中
            $index->add($doc);

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
