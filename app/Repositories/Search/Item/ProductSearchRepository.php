<?php namespace App\Repositories\Search\Item;

define('XS_APP_ROOT', config('services.search.root'));

use App\Repositories\Product\ProductRepositoryContract;
use App\Repositories\Search\SearchRepositoryContract;
use App\Repositories\Product\ProductProtocol;
use XS;
use XSDocument;

class ProductSearchRepository implements SearchRepositoryContract {


    protected $xs;
    /**
     * @var ProductRepositoryContract
     */
    private $productRepo;

    /**
     * ProductSearchRepository constructor.
     * @param ProductRepositoryContract $productRepo
     */
    public function __construct(ProductRepositoryContract $productRepo)
    {
        $this->xs = new XS(config('services.search.name'));
        $this->productRepo = $productRepo;
    }

    protected function getSearch()
    {
        return $this->xs->search;
    }

    protected function getIndex()
    {
        return $this->xs->index;
    }

    public function init()
    {
        $this->getIndex()->beginRebuild();

        $products = $this->productRepo->getAllProducts();

        if (!count($products)) return;

        foreach ($products as $product) {
            $this->create($product);
        }

        $this->getIndex()->endRebuild();
    }

    public function get($keyword = null)
    {
        $query = $this->getSearch()->setLimit(ProductProtocol::PRODUCT_PER_PAGE, $this->getOffset());
        if (!is_null($keyword)) {
            $query = $query->setQuery($keyword);
        }
        $products = $query->search();

        return $products;
    }

    public function create($product)
    {
        if ($product->status == ProductProtocol::VAR_PRODUCT_STATUS_DOWN) {
            $this->delete($product);
            return 1;
        };

        $data = $this->transform($product);

        // 创建文档对象
        $doc = new XSDocument;
        $doc->setFields($data);
        // 添加到索引数据库中
        $this->getIndex()->add($doc);
    }

    public function update($product)
    {
        if ($product->status == ProductProtocol::VAR_PRODUCT_STATUS_DOWN) {
            $this->delete($product);
            return 1;
        };

        $data = $this->transform($product);

        // 创建文档对象
        $doc = new XSDocument;
        $doc->setFields($data);
        // 添加到索引数据库中
        $this->getIndex()->update($doc);
    }

    public function delete($product)
    {
        $this->getIndex()->del($product['id']);
    }

    /**
     * @return mixed
     */
    private function getOffset()
    {
        return (\Request::get('page', 1) - 1) * ProductProtocol::PRODUCT_PER_PAGE;
    }

    public function hot($limit = 6)
    {
        return $this->getSearch()->getHotQuery($limit);
    }

    protected function transform($product){
        return [
            'id' => $product['id'], // 此字段为主键，必须指定
            'title' => $product['title'],
        ];
    }
}
