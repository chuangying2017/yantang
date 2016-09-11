<?php namespace App\Services\Invoice;

use App\Repositories\Invoice\InvoiceProtocol;
use App\Repositories\Invoice\StationInvoiceRepository;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;

class StationInvoiceService implements InvoiceServiceContract {

    /**
     * @var StationInvoiceRepository
     */
    private $stationInvoiceRepo;
    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * @var StationRepositoryContract
     */
    private $stationRepo;

    /**
     * StationInvoiceService constructor.
     * @param StationInvoiceRepository $stationInvoiceRepo
     * @param PreorderRepositoryContract $preorderRepo
     * @param StationRepositoryContract $stationRepo
     */
    public function __construct(StationInvoiceRepository $stationInvoiceRepo, PreorderRepositoryContract $preorderRepo, StationRepositoryContract $stationRepo)
    {
        $this->stationInvoiceRepo = $stationInvoiceRepo;
        $this->preorderRepo = $preorderRepo;
        $this->stationRepo = $stationRepo;
    }

    public function settleAll($invoice_date)
    {
        $stations = $this->stationRepo->getAllActive();

        try {
            $start_time = $this->getStartTime($invoice_date);
            $end_time = $this->getEndTime($invoice_date);

            foreach ($stations as $station) {
                $this->settleMerchant($station, $invoice_date, $start_time, $end_time);
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    public function settleMerchant($station, $invoice_date, $start_time, $end_time)
    {
        $station_id = $station['id'];
        $invoices = $this->stationInvoiceRepo->getAllOfMerchant($station_id, $invoice_date);

        if ($invoices->first()) {
            info('服务部 ' . $station['name'] . ' 于' . $invoice_date . '的账单已出', $invoices->first());
            return $invoices->first();
        }

        $orders = $this->getAllInvoiceOrders($station_id, $start_time, $end_time);

        $invoice_data = [
            'invoice_date' => $invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => $station_id,
            'merchant_name' => $station['name'],
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
            'detail' => []
        ];

        foreach ($orders as $order_key => $order) {
            $invoice_order = $this->getOrderDetail($order);
            $invoice_date['detail'][$order_key] = $invoice_order;

            $invoice_date['total_amount'] += $invoice_order['total_amount'];
            $invoice_date['discount_amount'] += $invoice_order['discount_amount'];
            $invoice_date['pay_amount'] += $invoice_order['pay_amount'];
            $invoice_date['service_amount'] += $invoice_order['service_amount'];
            $invoice_date['receive_amount'] += $invoice_order['receive_amount'];
        }

        return $this->stationInvoiceRepo->create($invoice_data);

    }
    
    protected function getStartTime($invoice_date)
    {
        $end_date = $this->getEndTime($invoice_date);

        switch ($end_date->day) {
            case 10:
                return $end_date->copy()->subMonth()->day(26)->startOfDay();
            case 25:
                return $end_date->copy()->day(11)->startOfDay();
            default:
                throw new \Exception('结算时间错误');
        }
    }

    /**
     * @param $invoice_date
     * @return Carbon
     */
    protected function getEndTime($invoice_date)
    {
        return Carbon::parse($invoice_date)->endOfDay();
    }

    protected function getAllInvoiceOrders($station_id, $start_time, $end_time)
    {
        $orders = $this->preorderRepo->getAll($station_id, null, null, PreorderProtocol::ASSIGN_STATUS_OF_ASSIGNED, $start_time, $end_time, PreorderProtocol::TIME_NAME_OF_CONFIRM);

        $orders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain');
            },
            'staff' => function ($query) {
                $query->select('id', 'name');
            }]);

        return $orders;
    }

    protected function getOrderDetail($order)
    {
        $data = [
            'preorder_id' => $order['id'],
            'order_id' => $order['order_id'],
            'order_no' => $order['order_no'],
            'status' => $order['status'],
            'name' => $order['name'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'staff_id' => $order['staff']['id'],
            'staff_name' => $order['staff']['name'],
            'total_amount' => $order['order']['total_amount'],
            'discount_amount' => $order['order']['discount_amount'],
            'pay_amount' => $order['order']['pay_amount'],
            'service_amount' => InvoiceProtocol::calServiceAmount($order['order']['pay_amount']),
            'detail' => json_encode($order['skus'])
        ];

        $data['receive_amount'] = $data['pay_amount'] - $data['service_amount'];

        return $data;
    }


}
