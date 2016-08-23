<?php namespace App\Services\Order\Generator;

use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Product\Sku\ProductSkuStockRepositoryContract;

class GetSkuInfo extends GenerateHandlerAbstract {

    /**
     * @var ProductSkuRepositoryContract
     */
    private $skuRepo;
    /**
     * @var ProductSkuStockRepositoryContract
     */
    private $productSkuStockRepo;

    /**
     * GetSkuInfo constructor.
     * @param ProductSkuRepositoryContract $skuRepo
     */
    public function __construct(ProductSkuRepositoryContract $skuRepo, ProductSkuStockRepositoryContract $productSkuStockRepo)
    {
        $this->skuRepo = $skuRepo;
        $this->productSkuStockRepo = $productSkuStockRepo;
    }


    public function handle(TempOrder $temp_order)
    {
        $request_sku_info = array_pluck($temp_order->getSkus(), 'quantity', 'product_sku_id');
        $request_sku_subscribe_info = array_pluck($temp_order->getSkus(), 'per_day', 'product_sku_id');
        $skus = $this->skuRepo->getSkus(array_keys($request_sku_info));

        if (!count($skus)) {
            throw new \Exception('商品不存在');
        }

        $stock_ok = true;
        foreach ($skus as $key => $sku) {
            $skus[$key]['quantity'] = $request_sku_info[$sku['id']];
            $skus[$key]['per_day'] = $request_sku_subscribe_info[$sku['id']];
            if (!$this->productSkuStockRepo->enoughStock($sku['id'], $skus[$key]['quantity'])) {
                $skus[$key]['stock_enough'] = false;
                $stock_ok = false;
                $temp_order->setError('商品 ID:' . $sku['id'] . ' ' . $sku['name'] . '库存不足');
            }
        }

        if (!$stock_ok) {
            return $temp_order;
        }

        $temp_order->setSkus($skus);

        return $this->next($temp_order);
    }
}
