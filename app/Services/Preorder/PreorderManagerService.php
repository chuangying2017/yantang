<?php namespace App\Services\Preorder;

use App\Events\Preorder\AssignIsConfirm;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use Carbon\Carbon;

class PreorderManagerService implements PreorderManageServiceContract {

    /**
     * @var PreorderRepositoryContract
     */
    private $orderRepo;
    /**
     * @var PreorderAssignRepositoryContract
     */
    private $assignRepo;
    /**
     * @var ProductSkuRepositoryContract
     */
    private $skuRepo;


    /**
     * PreorderManagerService constructor.
     * @param PreorderRepositoryContract $orderRepo
     * @param PreorderAssignRepositoryContract $assignRepo
     * @param ProductSkuRepositoryContract $skuRepo
     */
    public function __construct(
        PreorderRepositoryContract $orderRepo,
        PreorderAssignRepositoryContract $assignRepo,
        ProductSkuRepositoryContract $skuRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->assignRepo = $assignRepo;
        $this->skuRepo = $skuRepo;
    }

    public function init($order_id, $weekdays_product_skus, $start_time, $end_time = null)
    {
        $assign = $this->assignRepo->get($order_id);

        $product_skus = $this->getProductSkus($weekdays_product_skus);

        $order = $this->orderRepo->updatePreorder($order_id, $start_time, $this->getEndTime($start_time, $end_time), $product_skus, $assign['station_id']);

        event(new AssignIsConfirm($order));

        return $order;
    }

    protected function getProductSkus($weekdays_product_skus)
    {
        $sku_ids = [];
        foreach ($weekdays_product_skus as $weekday_product_skus) {
            foreach ($weekday_product_skus['product_skus'] as $weekday_product_sku) {
                if (!in_array($weekday_product_sku['product_sku_id'], $sku_ids)) {
                    array_push($sku_ids, $weekday_product_sku['product_sku_id']);
                }
            }
        }

        $skus = $this->skuRepo->getSkus($sku_ids);
        if (!count($skus)) {
            throw new \Exception('订购商品不存在');
        }

        foreach ($weekdays_product_skus as $weekday_key => $weekday_product_skus) {
            foreach ($weekday_product_skus['product_skus'] as $product_sku_key => $weekday_product_sku) {
                foreach ($skus as $sku) {
                    if ($weekday_product_sku['product_sku_id'] == $sku['id']) {
                        if (!is_numeric($sku['subscribe_price']) || !($sku['subscribe_price'] > 0)) {
                            throw new \Exception('商品' . $sku['name'] . $sku['id'] . ' 不能订购');
                        }
                        $weekdays_product_skus[$weekday_key]['product_skus'][$product_sku_key]['name'] = $sku['name'];
                        $weekdays_product_skus[$weekday_key]['product_skus'][$product_sku_key]['price'] = $sku['subscribe_price'];
                    }
                }
            }
        }

        return $weekdays_product_skus;
    }

    protected function getEndTime($start_time, $end_time)
    {
        if (!is_null($end_time)) {
            if ($end_time > $start_time) {
                throw new \Exception('结束时间不能早于开始时间');
            }
            return $end_time;
        }

        return Carbon::createFromFormat('Y-m-d', strtotime($start_time))->addYear(10);
    }

    public function change($order_id, $product_skus, $start_time = null, $end_time = null)
    {
        $old_order = $this->orderRepo->get($order_id);



        $order = $this->orderRepo->updatePreorder($order_id, $start_time, $this->getEndTime($start_time, $end_time), $product_skus, $assign['station_id']);
    }

    protected function orderIsPending($order)
    {
        $now = Carbon::now();
        return $order['start_time'] > $now && $order['end_time'] < $now
    }

    public function pause($order_id, $pause_time, $restart_time = null)
    {
        // TODO: Implement pause() method.
    }

}
