<?php namespace App\Services\Preorder;

use App\Events\Preorder\PaidPreorderBillingFail;
use App\Repositories\Preorder\Deliver\PreorderDeliverRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use App\Services\Pay\Exception\NotEnoughException;
use Carbon\Carbon;

class PreorderSettleService implements PreorderSettleServiceContract {

    /**
     * @var StationPreorderRepositoryContract
     */
    private $stationPreorderRepo;
    /**
     * @var PreorderDeliverRepositoryContract
     */
    private $deliverRepo;

    /**
     * @var StationRepositoryContract
     */
    private $stationRepo;
    /**
     * @var PreorderSkusRepositoryContract
     */
    private $skuRepo;

    /**
     * PreorderSettleService constructor.
     * @param StationPreorderRepositoryContract $stationPreorderRepo
     * @param PreorderDeliverRepositoryContract $deliverRepo
     */
    public function __construct(
        StationPreorderRepositoryContract $stationPreorderRepo,
        PreorderDeliverRepositoryContract $deliverRepo,
        StationRepositoryContract $stationRepo,
        PreorderSkusRepositoryContract $skuRepo
    )
    {
        $this->stationPreorderRepo = $stationPreorderRepo;
        $this->deliverRepo = $deliverRepo;
        $this->stationRepo = $stationRepo;
        $this->skuRepo = $skuRepo;
    }

    public function settle()
    {
        $station_ids = $this->stationRepo->getAll(true);
        foreach ($station_ids as $station_id) {
            \DB::beginTransaction();
            $this->settleStation($station_id);
            \DB::commit();
        }
    }

    protected function settleStation($station_id)
    {
        $orders = $this->stationPreorderRepo->getDayPreorderWithProductsByStation($station_id, Carbon::yesterday());

        $orders = $this->filterNotDeliverOrders($orders, $station_id);

        foreach ($orders as $order) {
            $deliver_sku_relate_ids = [];
            $data = [
                'user_id' => $order['user_id'],
                'preorder_id' => $order['id'],
                'station_id' => $order['station_id'],
                'staff_id' => $order['staff_id'],
                'deliver_at' => Carbon::yesterday()
            ];

            if (!count($order['skus']) || !$this->deliverRepo->getRecentDeliver($order['id'], Carbon::yesterday())) {
                continue;
            }


            #todo 完成订单

            foreach ($order['skus'] as $deliver_sku) {
                $sku_deliver_quantity = ($deliver_sku['quantity'] > $deliver_sku['remain']) ? $deliver_sku['remain'] : $deliver_sku['quantity'];

                $deliver_sku_relate_ids[$deliver_sku['id']] = ['quantity' => $sku_deliver_quantity];
                $this->skuRepo->decrement($deliver_sku['id'], $sku_deliver_quantity);
            }
            //生成发货记录
            $deliver = $this->deliverRepo->createDeliver($data);
            $deliver = $this->deliverRepo->updateAsSuccess($deliver);
            $deliver->skus()->attach($deliver_sku_relate_ids);
        }
    }

    protected function filterNotDeliverOrders($orders, $station_id)
    {
        return $orders;
    }
}
