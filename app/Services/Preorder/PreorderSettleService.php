<?php namespace App\Services\Preorder;

use App\Events\Preorder\PaidPreorderBillingFail;
use App\Repositories\Billing\PreorderBillingRepository;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use App\Services\Billing\PreorderBillingService;
use App\Services\Client\Account\WalletService;
use App\Services\Pay\Exception\NotEnoughException;
use Carbon\Carbon;

class PreorderSettleService implements PreorderSettleServiceContract {

    /**
     * @var StationPreorderRepositoryContract
     */
    private $stationPreorderRepo;
    /**
     * @var PreorderBillingRepository
     */
    private $billingRepo;
    /**
     * @var WalletService
     */
    private $wallet;
    /**
     * @var PreorderBillingService
     */
    private $preorderBillingService;
    /**
     * @var StationRepositoryContract
     */
    private $stationRepo;

    /**
     * PreorderSettleService constructor.
     * @param StationPreorderRepositoryContract $stationPreorderRepo
     * @param PreorderBillingRepository $billingRepo
     */
    public function __construct(
        StationPreorderRepositoryContract $stationPreorderRepo,
        PreorderBillingRepository $billingRepo,
        WalletService $wallet,
        PreorderBillingService $preorderBillingService,
        StationRepositoryContract $stationRepo
    )
    {
        $this->stationPreorderRepo = $stationPreorderRepo;
        $this->billingRepo = $billingRepo;
        $this->wallet = $wallet;
        $this->preorderBillingService = $preorderBillingService;
        $this->stationRepo = $stationRepo;
    }

    public function settle()
    {
        $station_ids = $this->stationRepo->getAll(true);
        foreach ($station_ids as $station_id) {
            $this->settleStation($station_id);
        }
    }

    protected function settleStation($station_id)
    {
        $orders = $this->stationPreorderRepo->getDayPreordersOfStation($station_id, null, Carbon::yesterday());
        $orders = $this->filterNotDeliverOrders($orders, $station_id);

        foreach ($orders as $order) {
            $settle_amount = 0;
            $billing_sku_relate_ids = [];
            $entity_ids = [
                'user_id' => $order['user_id'],
                'preorder_id' => $order['preorder_id'],
                'station_id' => $order['station_id'],
                'staff_id' => $order['staff_id'],
            ];
            foreach ($order['skus'] as $deliver_sku) {
                $settle_amount += $this->getSkuSettleAmount($deliver_sku);
                $billing_sku_relate_ids[] = $deliver_sku['id'];
            }
            //生成账单
            $billing = $this->billingRepo->createBilling($settle_amount, $entity_ids);
            $billing->skus()->attach($billing_sku_relate_ids);
            try {
                //支付账单
                if ($this->wallet->pay($this->preorderBillingService->setID($billing))) {
                    $this->billingRepo->updateAsPaid($billing);
                }
            } catch (NotEnoughException $e) {
                event(new PaidPreorderBillingFail($billing));
            }

        }
    }

    protected function getSkuSettleAmount($sku)
    {
        return $sku['total_amount'];
    }

    protected function filterNotDeliverOrders($orders, $station_id)
    {
        return $orders;
    }
}
