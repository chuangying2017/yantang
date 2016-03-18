<?php namespace App\Services\Product\Search;

define('XS_APP_ROOT', env('SEARCH_INI_PATH'));

use App\Models\Product;
use XS;
use XSDocument;


class ProductSearchService {


    protected $xs;

    public function __construct()
    {
        $this->xs = new XS(env('SEARCH_APP_NAME'));
    }

    public function getSearch()
    {
        return $this->xs->search;
    }

    public function getIndex()
    {
        return $this->xs->index;
    }

    public function build()
    {
        $this->getIndex()->beginRebuild();
        $products = Product::with('meta')->get();

        if ( ! count($products)) return;

        foreach ($products as $product) {
            $this->add($product);
        }

        $this->getIndex()->endRebuild();
    }

    public function hot()
    {
        return $this->getSearch()->getHotQuery();
    }

    public function add($product)
    {
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
        // 添加到索引数据库中
        $this->getIndex()->add($doc);
    }

    public function delete($product)
    {
        $this->getIndex()->del($product['id']);
    }


}
