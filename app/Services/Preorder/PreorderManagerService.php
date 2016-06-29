<?php namespace App\Services\Preorder;

use App\Events\Preorder\AssignIsConfirm;
use App\Models\Subscribe\PreorderSku;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
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
        PreorderSkusRepositoryContract $orderSkuRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->assignRepo = $assignRepo;
        $this->skuRepo = $skuRepo;
        $this->orderSkuRepo = $orderSkuRepo;
    }

    public function init($order_id, $weekdays_product_skus, $start_time, $end_time = null)
    {
        $order = $this->orderRepo->get($order_id);
        $assign = $this->assignRepo->get($order['id']);

        $product_skus = $this->getProductSkus($weekdays_product_skus);

        $order = $this->orderRepo->updatePreorderByStation($order, $start_time, $this->getEndTime($start_time, $end_time), $product_skus, $assign['station_id']);

        $this->assignRepo->updateAssignAsConfirm($order_id);
        $this->orderRepo->updatePreorderStatus($order_id, PreorderProtocol::ORDER_STATUS_OF_SHIPPING);

        event(new AssignIsConfirm($order));

        return $order;
    }

    protected function getProductSkus($weekdays_product_skus)
    {
        if (is_null($weekdays_product_skus)) {
            return null;
        }

        if ($weekdays_product_skus instanceof PreorderSku) {
            return $weekdays_product_skus->toArray();
        }

        $sku_ids = [];
        foreach ($weekdays_product_skus as $weekday_product_skus) {
            foreach ($weekday_product_skus['skus'] as $weekday_product_sku) {
                if (!in_array($weekday_product_sku['product_sku_id'], $sku_ids)) {
                    array_push($sku_ids, $weekday_product_sku['product_sku_id']);
                }
            }
        }

        $skus = $this->skuRepo->getSkus($sku_ids);
        if (!count($skus)) {
            throw new \Exception('订购商品不存在');
        }

        $order_skus = [];
        foreach ($weekdays_product_skus as $weekday_product_skus) {
            foreach ($weekday_product_skus['skus'] as $weekday_product_sku) {
                foreach ($skus as $sku) {
                    if ($weekday_product_sku['product_sku_id'] == $sku['id']) {
                        if (!is_numeric($sku['subscribe_price']) || !($sku['subscribe_price'] > 0)) {
                            throw new \Exception('商品' . $sku['name'] . $sku['id'] . ' 不能订购');
                        }
                        $order_skus[] = [
                            'weekday' => $weekday_product_skus['weekday'],
                            'daytime' => $weekday_product_skus['daytime'],
                            'product_sku_id' => $weekday_product_sku['product_sku_id'],
                            'quantity' => $weekday_product_sku['quantity'],
                            'name' => $sku['name'],
                            'price' => $sku['subscribe_price'],
                            'cover_image' => $sku['cover_image'],
                            'total_amount' => $weekday_product_sku['quantity'] * $sku['subscribe_price']
                        ];
                    }
                }
            }
        }

        return $order_skus;
    }

    protected function getEndTime($start_time, $end_time)
    {
        if (!is_null($end_time)) {
            if ($end_time < $start_time) {
                throw new \Exception('结束时间不能早于开始时间');
            }
            return $end_time;
        }

        return Carbon::createFromFormat('Y-m-d', strtotime($start_time))->addYear(10);
    }

    public function change($order_id, $weekdays_product_skus = null, $start_time = null, $end_time = null)
    {
        $old_order = $this->orderRepo->get($order_id);
        $now = Carbon::now();

        //未开始订单,直接修改
        if ($old_order['start_time'] > $now) {
            $order = $this->orderRepo->updatePreorderByStation($order_id, $start_time, $end_time, $this->getProductSkus($weekdays_product_skus));
        } else if ($old_order['end_time'] < $now) {
            //已结束订单,创建新订单
            if (is_null($weekdays_product_skus)) {
                $weekdays_product_skus = $this->orderSkuRepo->getAll($old_order['id']);
            }
            $this->createAndInitOrder($old_order, $start_time, $end_time, $weekdays_product_skus);
        } else {
            //进行中订单

            //修改进行中订单的结束时间
            $old_order_end_time = is_null($start_time) ? Carbon::today() : $start_time;
            $this->orderRepo->updatePreorderByStation($order_id, null, $old_order_end_time);

            //商品修改,创建新订单
            if (!is_null($weekdays_product_skus)) {
                $this->createAndInitOrder($old_order, $old_order_end_time, $this->getEndTime($start_time, $end_time), $weekdays_product_skus);
            }

            //短暂修改,克隆现有订单为将来订单
            if (!is_null($end_time)) {
                $this->createAndInitOrder($old_order, $start_time, $end_time, $weekdays_product_skus);
            }
        }
    }

    protected function createAndInitOrder($old_order, $start_time, $end_time, $weekdays_product_skus)
    {
        $new_order = $this->orderRepo->createPreorder(array_only($old_order, [
            'user_id',
            'name',
            'phone',
            'district_id',
            'address',
            'station_id'
        ]));
        return $this->init($new_order, $weekdays_product_skus, $start_time, $end_time);
    }

    public function pause($order_id, $pause_time, $restart_time = null)
    {
        $order = $this->orderRepo->get($order_id);

        $this->orderRepo->updatePreorderByStation($order, null, $pause_time, $restart_time);
    }

    public function charged($user_id)
    {
        $orders = $this->orderRepo->getAllByUser($user_id, PreorderProtocol::ORDER_STATUS_OF_SHIPPING, Carbon::now());
        if (count($orders)) {
            foreach ($orders as $order) {
                $this->orderRepo->updatePreorderChargeStatus($order['id'], PreorderProtocol::CHARGE_STATUS_OF_OK);
            }
        }

        $orders = $this->orderRepo->getAllByUser($user_id, PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);
        if (count($orders)) {
            foreach ($orders as $order) {
                $this->orderRepo->updatePreorderChargeStatus($order['id'], PreorderProtocol::CHARGE_STATUS_OF_OK);
            }
        }
    }

    public function needCharge($user_id)
    {
        $orders = $this->orderRepo->getAllByUser($user_id, PreorderProtocol::ORDER_STATUS_OF_SHIPPING, Carbon::now());
        if (count($orders)) {
            foreach ($orders as $order) {
                $this->orderRepo->updatePreorderChargeStatus($order['id'], PreorderProtocol::CHARGE_STATUS_OF_NOT_ENOUGH);
            }
        }
    }
}
