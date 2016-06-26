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

        $order = $this->orderRepo->updatePreorder($order_id, $start_time, $this->getEndTime($start_time, $end_time), $product_skus, $station_id);

        event(new AssignIsConfirm($order));


    }

    protected function getProductSkus($weekdays_product_skus)
    {

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
        // TODO: Implement change() method.
    }

    public function pause($order_id, $pause_time, $restart_time = null)
    {
        // TODO: Implement pause() method.
    }
}
