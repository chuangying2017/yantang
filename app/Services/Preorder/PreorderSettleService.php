<?php namespace App\Services\Preorder;

use App\Events\Preorder\PreorderSkusOut;
use App\Repositories\Preorder\Deliver\PreorderDeliverRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
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

    public function settle($date = null)
    {
        $station_ids = $this->stationRepo->getAll(true);
        $date = $this->getSettleDate($date);
        foreach ($station_ids as $station_id) {
            \DB::beginTransaction();
            $this->settleStation($station_id, $date);
            \DB::commit();
        }
    }

    protected function settleStation($station_id, Carbon $date = null)
    {
        $orders = $this->stationPreorderRepo->getDayPreorderWithProductsByStation($station_id, $date->copy());

        $orders = $this->filterNotDeliverOrders($orders, $station_id);

        foreach ($orders as $order) {
            $deliver_sku_relate_ids = [];
            $data = [
                'user_id' => $order['user_id'],
                'preorder_id' => $order['id'],
                'station_id' => $order['station_id'],
                'staff_id' => $order['staff_id'],
                'deliver_at' => $date
            ];

            if (!count($order['skus']) || $this->deliverRepo->getRecentDeliver($order['id'], $date)) {
                continue;
            }

            $out_sku = 0;
            foreach ($order['skus'] as $deliver_sku) {
                $sku_deliver_quantity = ($deliver_sku['quantity'] > $deliver_sku['remain']) ? $deliver_sku['remain'] : $deliver_sku['quantity'];
                if ($sku_deliver_quantity == $deliver_sku['remain']) {
                    $out_sku++;
                }
                $deliver_sku_relate_ids[$deliver_sku['id']] = ['quantity' => $sku_deliver_quantity];
                $this->skuRepo->decrement($deliver_sku['id'], $sku_deliver_quantity);
            }

            if ($out_sku == count($order['skus'])) {
                event(new PreorderSkusOut($order));
            }
            //生成发货记录
            $deliver = $this->deliverRepo->createDeliver($data);
            $deliver = $this->deliverRepo->updateAsSuccess($deliver);
            $deliver->skus()->attach($deliver_sku_relate_ids);
        }

    }

	/**
     * @param $date
     * @return Carbon
     */
    protected function getSettleDate($date)
    {
        $date = is_null($date) ? Carbon::yesterday() : Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        return $date >= Carbon::today() ? Carbon::yesterday() : $date;
    }

    protected function filterNotDeliverOrders($orders, $station_id)
    {
        return $orders;
    }
}
