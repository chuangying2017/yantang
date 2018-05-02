<?php namespace App\Services\Invoice;

use App\Models\Collect\CollectOrder;
use App\Repositories\Invoice\InvoiceProtocol;
use App\Repositories\Invoice\StationAdminInvoiceRepository;
use App\Repositories\Invoice\StationInvoiceRepository;
use App\Repositories\Invoice\StationUnInvoiceRepository;
use App\Repositories\Invoice\StationRefundInvoiceRepository;
use App\Repositories\Invoice\StationGiftcardInvoiceRepository;
use App\Repositories\Order\CollectOrderRepository;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use EasyWeChat;
use Excel;
use Storage;

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

    const ALL_BILL_COLLUMN_TRANSACTION_DATE =  0; //交易时间
    const ALL_BILL_COLLUMN_APP_ID =  1; //公众账号ID
    const ALL_BILL_COLLUMN_MERCHANT_ID =  2; //商户号
    const ALL_BILL_COLLUMN_SUB_MERCHANT_ID =  3; //子商户号
    const ALL_BILL_COLLUMN_DEVICE_NO =  4; //设备号
    const ALL_BILL_COLLUMN_TRANSACTION_ID =  5; //微信订单号
    const ALL_BILL_COLLUMN_OUT_TRADE_NO =  6; //商户订单号
    const ALL_BILL_COLLUMN_OPEN_ID =  7; //用户标识
    const ALL_BILL_COLLUMN_TRADE_TYPE =  8; //交易类型
    const ALL_BILL_COLLUMN_TRADE_STATE =  9; //交易状态
    const ALL_BILL_COLLUMN_BANK_TYPE = 10; //付款银行
    const ALL_BILL_COLLUMN_FEE_TYPE = 11; //货币种类
    const ALL_BILL_COLLUMN_TOTAL_FEE = 12; //总金额
    const ALL_BILL_COLLUMN_RED_ENVELOPE_FEE = 13; //企业红包金额
    const ALL_BILL_COLLUMN_REFUND_TRANSACTION_ID = 14; //微信退款单号
    const ALL_BILL_COLLUMN_REFUND_OUT_TRADE_NO = 15; //商户退款单号
    const ALL_BILL_COLLUMN_REFUND_FEE = 16; //退款金额
    const ALL_BILL_COLLUMN_REFUND_RED_ENVELOPE_FEE = 17; //企业红包退款金额
    const ALL_BILL_COLLUMN_REFUND_TYPE = 18; //退款类型
    const ALL_BILL_COLLUMN_REFUND_STATE = 19; //退款状态
    const ALL_BILL_COLLUMN_BODY = 20; //商品名称
    const ALL_BILL_COLLUMN_ATTACH = 21; //商户数据包
    const ALL_BILL_COLLUMN_FEE = 22; //手续费
    const ALL_BILL_COLLUMN_FEE_RATE = 23; //费率

    const SUCCESS_BILL_COLUMN_TRANSACTION_DATE = 0;
    const SUCCESS_BILL_COLUMN_APP_ID = 1;
    const SUCCESS_BILL_COLUMN_MERCHANT_ID = 2;
    const SUCCESS_BILL_COLUMN_SUB_MERCHANT_ID = 3;
    const SUCCESS_BILL_COLUMN_DEVICE_NO = 4;
    const SUCCESS_BILL_COLUMN_TRANSACTION_ID = 5;
    const SUCCESS_BILL_COLUMN_OUT_TRADE_NO = 6;
    const SUCCESS_BILL_COLUMN_OPEN_ID = 7;
    const SUCCESS_BILL_COLUMN_TRADE_TYPE = 8;
    const SUCCESS_BILL_COLUMN_TRADE_STATE = 9; //交易状态
    const SUCCESS_BILL_COLUMN_BANK_TYPE = 10;
    const SUCCESS_BILL_COLUMN_FEE_TYPE = 11;
    const SUCCESS_BILL_COLUMN_TOTAL_FEE = 12;
    const SUCCESS_BILL_COLUMN_RED_ENVELOPE_FEE = 13; //企业红包
    const SUCCESS_BILL_COLUMN_BODY = 14; //商品名称
    const SUCCESS_BILL_COLUMN_ATTACH = 15;
    const SUCCESS_BILL_COLUMN_FEE = 16;
    const SUCCESS_BILL_COLUMN_FEE_RATE = 17;

    /**
     * StationInvoiceService constructor.
     * @param StationInvoiceRepository $stationInvoiceRepo
     * @param PreorderRepositoryContract $preorderRepo
     * @param StationRepositoryContract $stationRepo
     * @param StationAdminInvoiceRepository $stationAdminInvoiceRepository
     * @param StationUnInvoiceRepository $stationUnInvoiceRepository
     */
    public function __construct(
        StationInvoiceRepository $stationInvoiceRepo,
        PreorderRepositoryContract $preorderRepo,
        StationRepositoryContract $stationRepo,
        StationAdminInvoiceRepository $stationAdminInvoiceRepository,
        StationUnInvoiceRepository $stationUnInvoiceRepository,
        StationRefundInvoiceRepository $stationRefundInvoiceRepository,
        StationGiftcardInvoiceRepository $stationGiftcardInvoiceRepository,
        CollectOrderRepository $collectRepo
    )
    {
        $this->stationInvoiceRepo = $stationInvoiceRepo;
        $this->preorderRepo = $preorderRepo;
        $this->stationRepo = $stationRepo;
        $this->stationAdminInvoiceRepository = $stationAdminInvoiceRepository;
        $this->stationUnInvoiceRepository = $stationUnInvoiceRepository;
        $this->stationRefundInvoiceRepository = $stationRefundInvoiceRepository;
        $this->stationGiftcardInvoiceRepository = $stationGiftcardInvoiceRepository;

        $this->collectRepo = $collectRepo;
    }

    public function settleAll($invoice_date)
    {
        try {
dump($invoice_date);
            //invoice_date是账单截止日期。若不加一天，则账单截止日期那天就可以跑对账了。
            if (Carbon::parse($invoice_date)->addDay()->isFuture()) {
                throw new \Exception('今天为' . Carbon::today()->toDateTimeString() . '未到' . $invoice_date);
            }

            $stations = $this->stationRepo->getAllActive();

            $start_time = $this->getStartTime($invoice_date);
            $end_time = $this->getEndTime($invoice_date);

            $this->settleGiftcard($invoice_date, $start_time, $end_time);
            $station_invoices = [];

            foreach ($stations as $station) {
                $station_invoices[] = $this->settleMerchant($station, $invoice_date, $start_time, $end_time);
            }

            $this->settleAdmin($station_invoices, $invoice_date, $start_time, $end_time);

            $this->settleUnConfirm($invoice_date, $start_time, $end_time);
            $this->settleUnConfirmHistory($invoice_date, $start_time, $end_time);
            $this->settleRefund($invoice_date, $start_time, $end_time);

        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }

    public function settleMerchant($station, $request_invoice_date, $start_time, $end_time)
    {
        $station_id = $station['id'];
        $invoices = $this->stationInvoiceRepo->getAll($station_id, $request_invoice_date, $request_invoice_date);

        if ($invoices->first()) {
            info('服务部 ' . $station['name'] . ' 于' . $request_invoice_date . '的账单已出', $invoices->first()->toArray());
            return $invoices->first();
        }

        $orders = $this->getAllInvoiceOrders($station_id, $start_time, $end_time);
        $collect_orders = $this->getCollectedOrders($station_id, $start_time, $end_time);

        $invoice_data = [
            'invoice_date' => $request_invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => $station_id,
            'merchant_name' => $station['name'],
            'merchant_no' => $station['merchant_no'],
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
            'detail' => []
        ];

        foreach ($orders as $order_key => $order) {
            $invoice_order = $this->getOrderDetail($order, $station);
            $invoice_data['detail'][$order_key] = $invoice_order;

            $invoice_data['total_amount'] += $invoice_order['total_amount'];
            $invoice_data['discount_amount'] += $invoice_order['discount_amount'];
            $invoice_data['pay_amount'] += $invoice_order['pay_amount'];
            $invoice_data['service_amount'] += $invoice_order['service_amount'];
            $invoice_data['receive_amount'] += $invoice_order['receive_amount'];
        }

        foreach ($collect_orders as $collect_order_key => $collect_order) {
            $invoice_order = $this->getOrderDetailFromCollectOrder($collect_order, $station);
            $invoice_data['collect_orders'][$collect_order_key] = $invoice_order;

            $invoice_data['total_amount'] += $invoice_order['total_amount'];
            $invoice_data['discount_amount'] += $invoice_order['discount_amount'];
            $invoice_data['pay_amount'] += $invoice_order['pay_amount'];
            $invoice_data['service_amount'] += $invoice_order['service_amount'];
            $invoice_data['receive_amount'] += $invoice_order['receive_amount'];
        }

        return $this->stationInvoiceRepo->create($invoice_data);
    }

    public function settleRefund($request_invoice_date, $start_time, $end_time)
    {
        $invoices = $this->stationRefundInvoiceRepository->getAll(InvoiceProtocol::ID_OF_REFUND_INVOICE, $request_invoice_date, $request_invoice_date);

        if ($invoices->first()) {
            info('本期退款订单 于' . $request_invoice_date . '的账单已出', $invoices->first()->toArray());
            return $invoices->first();
        }

        $orders = $this->getAllRefundOrders($start_time, $end_time);

        $invoice_data = [
            'invoice_date' => $request_invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => InvoiceProtocol::ID_OF_REFUND_INVOICE,
            'merchant_name' => '本期退款订单',
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
            'detail' => [],
        ];

        foreach ($orders as $order_key => $order) {
            $invoice_order = $this->getOrderDetail($order);
            $invoice_data['detail'][$order_key] = $invoice_order;

            $invoice_data['total_amount'] += $invoice_order['total_amount'];
            $invoice_data['discount_amount'] += $invoice_order['discount_amount'];
            $invoice_data['pay_amount'] += $invoice_order['pay_amount'];
            $invoice_data['service_amount'] += $invoice_order['service_amount'];
            $invoice_data['receive_amount'] += $invoice_order['receive_amount'];
        }

        return $this->stationRefundInvoiceRepository->create($invoice_data);
    }
    public function settleUnConfirm($request_invoice_date, $start_time, $end_time)
    {
        $invoices = $this->stationUnInvoiceRepository->getAll(InvoiceProtocol::ID_OF_UN_CONFIRM_INVOICE, $request_invoice_date, $request_invoice_date);

        if ($invoices->first()) {
            info('未确认订单 于' . $request_invoice_date . '的账单已出', $invoices->first()->toArray());
            return $invoices->first();
        }

        $orders = $this->getAllUnConfirmOrders($start_time, $end_time);

        $invoice_data = [
            'invoice_date' => $request_invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => InvoiceProtocol::ID_OF_UN_CONFIRM_INVOICE,
            'merchant_name' => '未确认订单',
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
            'detail' => [],
        ];

        foreach ($orders as $order_key => $order) {
            $invoice_order = $this->getOrderDetail($order);
            $invoice_data['detail'][$order_key] = $invoice_order;

            $invoice_data['total_amount'] += $invoice_order['total_amount'];
            $invoice_data['discount_amount'] += $invoice_order['discount_amount'];
            $invoice_data['pay_amount'] += $invoice_order['pay_amount'];
            $invoice_data['service_amount'] += $invoice_order['service_amount'];
            $invoice_data['receive_amount'] += $invoice_order['receive_amount'];
        }

        return $this->stationUnInvoiceRepository->create($invoice_data);
    }
    public function settleUnConfirmHistory($request_invoice_date, $start_time, $end_time)
    {
        $invoices = $this->stationUnInvoiceRepository->getAll(InvoiceProtocol::ID_OF_UN_CONFIRM_HISTORY_INVOICE, $request_invoice_date, $request_invoice_date);

        if ($invoices->first()) {
            info('历史未结算订单 于' . $request_invoice_date . '的账单已出', $invoices->first()->toArray());
            return $invoices->first();
        }

        $orders = $this->getUnConfirmHistoryOrders($start_time, $end_time);

        $invoice_data = [
            'invoice_date' => $request_invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => InvoiceProtocol::ID_OF_UN_CONFIRM_HISTORY_INVOICE,
            'merchant_name' => '历史未结算订单',
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
            'detail' => [],
        ];

        foreach ($orders as $order_key => $order) {
            $invoice_order = $this->getOrderDetail($order);
            $invoice_data['detail'][$order_key] = $invoice_order;

            $invoice_data['total_amount'] += $invoice_order['total_amount'];
            $invoice_data['discount_amount'] += $invoice_order['discount_amount'];
            $invoice_data['pay_amount'] += $invoice_order['pay_amount'];
            $invoice_data['service_amount'] += $invoice_order['service_amount'];
            $invoice_data['receive_amount'] += $invoice_order['receive_amount'];
        }

        return $this->stationUnInvoiceRepository->create($invoice_data);
    }

    public function settleAdmin($merchant_invoices, $request_invoice_date, $start_time, $end_time)
    {
        $invoices = $this->stationAdminInvoiceRepository->getAll(InvoiceProtocol::ID_OF_ADMIN_INVOICE, $request_invoice_date, $request_invoice_date);
        if ($invoices->first()) {
            info('总部 于' . $request_invoice_date . '的账单已出', $invoices->first()->toArray());
            return $invoices->first();
        }

        $invoice_data = [
            'invoice_date' => $request_invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => InvoiceProtocol::ID_OF_ADMIN_INVOICE,
            'merchant_name' => InvoiceProtocol::NAME_OF_ADMIN_INVOICE,
            'total_count' => 0,
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
        ];

        foreach ($merchant_invoices as $merchant_invoice) {
            $invoice_data['total_count'] += $merchant_invoice['total_count'];
            $invoice_data['total_amount'] += $merchant_invoice['total_amount'];
            $invoice_data['discount_amount'] += $merchant_invoice['discount_amount'];
            $invoice_data['pay_amount'] += $merchant_invoice['pay_amount'];
            $invoice_data['service_amount'] += $merchant_invoice['service_amount'];
            $invoice_data['receive_amount'] += $merchant_invoice['receive_amount'];
        }

        return $this->stationAdminInvoiceRepository->create($invoice_data);
    }

    public function settleGiftcard($request_invoice_date, $start_time, $end_time){
        $invoices = $this->stationGiftcardInvoiceRepository->getAll(InvoiceProtocol::ID_OF_GIFTCARD_INVOICE, $request_invoice_date, $request_invoice_date);
        if ($invoices->first()) {
            info('礼品卡 于' . $request_invoice_date . '的账单已出', $invoices->first()->toArray());
            return $invoices->first();
        }

        $payment = EasyWeChat::payment();

        $giftcardOrders = [];

        //download everyday bill
        for( $crntDate = Carbon::parse($start_time); $crntDate->lte(Carbon::parse($end_time)); $crntDate->addDay() ){
            //if file not exist
            $crntDateString = $crntDate->format('Ymd');
            $filepath = 'bill/'.$request_invoice_date.'/'.$crntDateString.'.csv';
            if( !Storage::disk('local')->has($filepath) ){
                //download
                //仅获取支付成功的
                $bill = $payment->downloadBill($crntDateString)->getContents();
                Storage::disk('local')->put($filepath, $bill);
            }


            Excel::load(storage_path('app/'.$filepath), function($reader) use (&$giftcardOrders){
                $reader->noHeading();

                $all = $reader->all();
                // skip 1 for header
                // abandon 2 in the end for total amount, add 1 with 2 to become 3, because starts from 0
                $bills = $all->slice(1,count($all)-3);
                $giftcardOrders += $bills->where(self::ALL_BILL_COLLUMN_BODY, '`购买礼品卡')
                                    ->where(self::ALL_BILL_COLLUMN_REFUND_TYPE, '`')
                                    ->toArray();
            });
        }

        $invoice_data = [
            'invoice_date' => $request_invoice_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'merchant_id' => InvoiceProtocol::ID_OF_GIFTCARD_INVOICE,
            'merchant_name' => InvoiceProtocol::NAME_OF_GIFTCARD_INVOICE,
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
        ];

        foreach ($giftcardOrders as $order_key => $giftcardOrder) {
            $total_amount = trim($giftcardOrder[self::ALL_BILL_COLLUMN_TOTAL_FEE],'`');
            $pay_amount = trim($giftcardOrder[self::ALL_BILL_COLLUMN_TOTAL_FEE],'`');
            $service_amount = InvoiceProtocol::calServiceAmount($pay_amount);
            $receive_amount = $pay_amount - $service_amount;

            $invoice_data['detail'][$order_key] = $giftcardOrder;

            $invoice_data['total_amount'] += floor($total_amount*100);
            $invoice_data['discount_amount'] += 0; //购买礼品卡不能使用优惠券 $giftcardOrder['discount_amount'];
            $invoice_data['pay_amount'] += floor($pay_amount*100);
            $invoice_data['service_amount'] += floor($service_amount*100);
            $invoice_data['receive_amount'] += floor($receive_amount*100);
        }
        // parse
        // filter used, unused, expired giftcard

        return $this->stationGiftcardInvoiceRepository->create($invoice_data);
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
        $orders = $this->preorderRepo->getAll($station_id, null, null, null, PreorderProtocol::ASSIGN_STATUS_OF_ASSIGNED, $start_time, $end_time, PreorderProtocol::TIME_NAME_OF_CONFIRM, InvoiceProtocol::PREORDER_INVOICE_ORDER_OF_DEFAULT);

        $orders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain', 'per_day');
            },
            'staff' => function ($query) {
                $query->select('id', 'name');
            }]);

        return $orders;
    }

    protected function getAllUnConfirmOrders($start_time, $end_time)
    {
        $orders = $this->preorderRepo->getAll(null, null, null, null, null, $start_time, $end_time, PreorderProtocol::TIME_NAME_OF_PAY, InvoiceProtocol::PREORDER_INVOICE_ORDER_OF_DEFAULT);

        $orders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain', 'per_day');
            },
            'staff' => function ($query) {
                $query->select('id', 'name');
            }]);

        return $orders;
    }

    protected function getUnConfirmHistoryOrders($start_time, $end_time)
    {
        $orders = $this->preorderRepo->getAll(null, null, null, null, PreorderProtocol::ORDER_STATUS_OF_ASSIGNING, null, $start_time, PreorderProtocol::TIME_NAME_OF_PAY, InvoiceProtocol::PREORDER_INVOICE_ORDER_OF_DEFAULT);

        $orders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain', 'per_day');
            },
            'staff' => function ($query) {
                $query->select('id', 'name');
            }]);

        return $orders;
    }

    protected function getAllRefundOrders($start_time, $end_time)
    {
        $orders = $this->preorderRepo->getAll(null, null, null, null, PreorderProtocol::ORDER_STATUS_OF_CANCEL, $start_time, $end_time, PreorderProtocol::TIME_NAME_OF_PAY, InvoiceProtocol::PREORDER_INVOICE_ORDER_OF_DEFAULT);

        $orders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain', 'per_day');
            },
            'staff' => function ($query) {
                $query->select('id', 'name');
            }]);

        return $orders;
    }

    protected function getOrderDetail($order, $station = null)
    {
        $data = [
            'preorder_id' => $order['id'],
            'order_id' => $order['order_id'],
            'order_no' => $order['order_no'],
            'status' => $order['status'],
            'name' => $order['name'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'staff_id' => $order['staff']['id'] ?: '',
            'staff_name' => $order['staff']['name'] ?: '',
            'total_amount' => $order['order']['total_amount'],
            'discount_amount' => $order['order']['discount_amount'],
            'pay_amount' => $order['order']['pay_amount'],
            'confirm_at' => $order['confirm_at'] ?: '',
            'order_at' => $order['created_at'],
            'service_amount' => InvoiceProtocol::calServiceAmount($order['order']['pay_amount']),
            'detail' => json_encode($order['skus'])
        ];

        if (!is_null($station)) {
            $data['station_id'] = $station['id'];
            $data['station_name'] = $station['name'];
        }

        $data['receive_amount'] = $data['pay_amount'] - $data['service_amount'];

        return $data;
    }

    protected function getCollectedOrders( $station_id, $start_time, $end_time)
    {
        $orders = $this->collectRepo->getPaidOrders( $station_id, $start_time, $end_time );
        $orders->load([
            'order' => function( $query ){
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'pay_amount');
            },
            'sku'=> function($query){
                $query->select('id','product_id','name', 'price', 'unit');
            },
            'staff' => function( $query ){
                $query->select('id', 'name');
            },
        ]);
        return $orders;
    }

    protected function getOrderDetailFromCollectOrder( CollectOrder $collect_order, $station = null )
    {
        $collect_order['sku']['quantity'] = $collect_order['quantity'];
        $data = [
            'collect_order_id' => $collect_order['id'],
            'order_id' => $collect_order['order_id'],
            'order_no' => $collect_order['order']['order_no'],
            'name' => $collect_order['address']['name'],
            'phone' => $collect_order['address']['phone'],
            'address' => $collect_order['address']['detail'],
            'staff_id' => $collect_order['staff_id'],
            'staff_name' => $collect_order['staff']['name'],
            'total_amount' => $collect_order['order']['total_amount'],
            'discount_amount' => $collect_order['order']['discount_amount'],
            'pay_amount' => $collect_order['order']['pay_amount'],
            'pay_at' => $collect_order['pay_at'],
            'service_amount' => InvoiceProtocol::calServiceAmount($collect_order['order']['pay_amount']),
            'detail' => json_encode($collect_order['sku']),
        ];

        if (!is_null($station)) {
            $data['station_id'] = $station['id'];
            $data['station_name'] = $station['name'];
        }

        $data['receive_amount'] = $data['pay_amount'] - $data['service_amount'];

        return $data;
    }

    /**
     * @var StationAdminInvoiceRepository
     */
    private $stationAdminInvoiceRepository;
    /**
     * @var StationUnInvoiceRepository
     */
    private $stationUnInvoiceRepository;


}
