<?php namespace App\Services\Preorder;

use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use Carbon\Carbon;

class PreorderManagerService implements PreorderManageServiceContract {

    /**
     * @var StationPreorderRepositoryContract
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
     * @var PreorderSkusRepositoryContract
     */
    private $orderSkuRepo;


    /**
     * PreorderManagerService constructor.
     * @param PreorderRepositoryContract $orderRepo
     * @param PreorderAssignRepositoryContract $assignRepo
     * @param ProductSkuRepositoryContract $skuRepo
     */
    public function __construct(
        StationPreorderRepositoryContract $orderRepo,
        PreorderAssignRepositoryContract $assignRepo,
        ProductSkuRepositoryContract $skuRepo,
        PreorderRepositoryContract $orderSkuRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->assignRepo = $assignRepo;
        $this->skuRepo = $skuRepo;
        $this->orderSkuRepo = $orderSkuRepo;
    }

    public function pause($order_id, $pause_time, $restart_time = null)
    {
        //未暂停过
        //已设置未执行
        //暂停后已重启, 先备份
        //直接覆盖
        if ($pause_time < Carbon::today()->addDays(config('services.subscribe.pause_days'))->toDateString()) {
            throw new \Exception('设置的暂停时间需在' . config('services.subscribe.pause_days') . '天后');
        }

        $preorder = $this->orderRepo->get($order_id);

        if ($preorder['status'] !== PreorderProtocol::ORDER_STATUS_OF_SHIPPING) {
            throw new \Exception('当前状态无法设置暂停');
        }

        //暂停中,未结束
        if (!is_null($preorder['pause_time'])) {
            if ($preorder['pause_time'] <= Carbon::today()->toDateString()) {
                if (is_null($preorder['restart_time']) || $preorder['restart_time'] > Carbon::today()->toDateString()) {
                    throw new \Exception('暂停配送中,无法设置暂停');
                } else {
                    // #todo 存储暂停历史
                }
            }
        }

        $preorder = $this->orderRepo->updatePreorderTime($preorder, $pause_time, $restart_time);

        return $preorder;
    }

    public function restart($order_id, $restart_time)
    {
        if ($restart_time < Carbon::today()->addDays(config('services.subscribe.pause_days'))->toDateString()) {
            throw new \Exception('设置的开始时间需在' . config('services.subscribe.pause_days') . '天后');
        }

        $preorder = $this->orderRepo->get($order_id);

        if ($preorder['status'] !== PreorderProtocol::ORDER_STATUS_OF_SHIPPING) {
            throw new \Exception('当前状态无法设置重新配送');
        }
        //已设置未执行
        //暂停中,未结束
        if (!is_null($preorder['pause_time'])) {
            if (is_null($preorder['restart_time']) || $preorder['restart_time'] > Carbon::today()->toDateString()) {
                $preorder = $this->orderRepo->updatePreorderTime($preorder, null, $restart_time);
            }
        }

        return $preorder;
    }

    public function stationDailyInfo($station_id, $date = null, $daytime = null)
    {
        $date = is_null($date) ? Carbon::today() : Carbon::createFromFormat('Y-m-d', $date);
        $orders = $this->orderRepo->getDayPreorderWithProductsByStation($station_id, $date, $daytime);

        return $this->transformOrder($orders);
    }

    protected function transformOrder($orders)
    {
        $product_skus_info = [];
        foreach ($orders as $key => $order) {
            if (!count($order['skus'])) {
                continue;
            }
            foreach ($order['skus'] as $sku) {
                $sku_key = $sku['product_sku_id'];
                if (isset($product_skus_info[$sku_key])) {
                    $product_skus_info[$sku_key]['quantity'] += $sku['quantity'];
                } else {
                    $product_skus_info[$sku_key]['product_id'] = $sku['product_id'];
                    $product_skus_info[$sku_key]['product_sku_id'] = $sku['product_sku_id'];
                    $product_skus_info[$sku_key]['quantity'] = $sku['quantity'];
                    $product_skus_info[$sku_key]['name'] = $sku['name'];
                    $product_skus_info[$sku_key]['cover_image'] = $sku['cover_image'];
                }
            }
        }

        return $product_skus_info;
    }

    public function staffDailyInfo($staff_id, $day = null, $daytime = null)
    {
        $day = is_null($day) ? Carbon::today() : Carbon::createFromFormat('Y-m-d', $day);
        $orders = $this->orderRepo->getDayPreorderWithProductsOfStaff($staff_id, $day, $daytime);

        return $this->transformOrder($orders);
    }


}
