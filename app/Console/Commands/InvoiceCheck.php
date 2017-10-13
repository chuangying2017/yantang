<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice\StationInvoiceOrder;
use App\Models\Invoice\StationInvoiceCollectOrder;
use App\Models\Pay\PingxxPayment;
use Carbon\Carbon;
use EasyWeChat;
use Excel;
use Log;
use Storage;

class InvoiceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:check {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check invoice';

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Debugbar::enable();
        $invoice_date = $this->argument('date');
        try {
            //invoice_date是账单截止日期。若不加一天，则账单截止日期那天就可以跑对账了。
            $allowDate = Carbon::parse($invoice_date)->addDay();
            $allowDate->hour = 10;
            if ($allowDate->isFuture()) {
                throw new \Exception('现在为' . Carbon::today()->toDateTimeString() . '未到' . $allowDate->toDateTimeString());
            }

            $start_time = $this->getStartTime($invoice_date);
            $end_time = $this->getEndTime($invoice_date);

            $orders = collect();
            for( $crntDate = Carbon::parse($start_time); $crntDate->lte(Carbon::parse($end_time)); $crntDate->addDay() ){
                //if file not exist
                $crntDateString = $crntDate->format('Ymd');
                $filepath = 'bill/'.$invoice_date.'/'.$crntDateString.'.csv';
                if( !Storage::disk('local')->has($filepath) ){
                    $payment = EasyWeChat::payment();
                    //download
                    $bill = $payment->downloadBill($crntDateString)->getContents();
                    Storage::disk('local')->put($filepath, $bill);
                }

                Excel::load(storage_path('app/'.$filepath), function($reader) use (&$orders){
                    $reader->noHeading();
                    $all = $reader->all();
                    // skip 1 for header
                    // abandon 2 in the end for total amount, add 1 with 2 to become 3, because starts from 0
                    $orders = $orders->merge($all->slice(1,count($all)-3));
                });
            }
            // $subscribes = $orders->where(self::ALL_BILL_COLLUMN_BODY,'`燕塘优鲜达订单');
            $subscribes = $orders;
            $successIds = $subscribes->where(self::ALL_BILL_COLLUMN_TRADE_STATE, '`SUCCESS')->pluck(self::ALL_BILL_COLLUMN_TRANSACTION_ID);
            $refundIds = $subscribes->where(self::ALL_BILL_COLLUMN_TRADE_STATE, '`REFUND')->pluck(self::ALL_BILL_COLLUMN_TRANSACTION_ID);
            $transactions = $successIds->diff($refundIds)->map(function($item,$key){
                return trim($item,'`');
            });


            // get transaction_id from invoice_orders
            $minInvoiceDate = Carbon::parse($invoice_date);
            if($minInvoiceDate->day == 10){
                $minInvoiceDate->subMonth()->day = 25;
            }
            else{
                $minInvoiceDate->day = 10;
            }
            $minInvoiceNo = Carbon::parse($invoice_date)->format('10Ymd000000'); // add previous unconfirm
            $maxInvoiceNo = Carbon::parse($invoice_date)->format('10Ymd900000');

            $unconfirmInvoicePaymentPreorderIds = StationInvoiceOrder::where('invoice_no', $minInvoiceDate->format('10Ymd999999') )
                                        ->pluck('preorder_id');

            $invoicePayments = StationInvoiceOrder::where(function($query) use ($minInvoiceNo, $maxInvoiceNo, $invoice_date,$unconfirmInvoicePaymentPreorderIds){
                                            $query->whereBetween('invoice_no', [$minInvoiceNo, $maxInvoiceNo])
                                                    ->orWhere('invoice_no', Carbon::parse($invoice_date)->format('10Ymd999999'));
                                        })
                                        ->whereNotIn('preorder_id',$unconfirmInvoicePaymentPreorderIds)
                                        ->with('order.billings')
                                        ->get();//eager load morph has bug
            $billingIds = $invoicePayments->pluck('order.billings.0.id');

            $invoiceCollectOrders = StationInvoiceCollectOrder::whereBetween('invoice_no', [$minInvoiceNo, $maxInvoiceNo])
                                        ->with('order.billings')
                                        ->get();
            $collectOrderBillingIds = $invoiceCollectOrders->pluck('order.billings.0.id');

            $invoiceTransactionIds = PingxxPayment::whereIn('billing_id',$billingIds->merge($collectOrderBillingIds))
                            ->where('refunded',0)
                            ->where('paid',1)
                            ->pluck('transaction_no');

            $inInvoiceNotInWechat = $invoiceTransactionIds->diff($transactions);
            echo '在invoice中，不在微信对账单中的有：',count($inInvoiceNotInWechat),"个\n";
            echo implode(',',$inInvoiceNotInWechat->toArray()), "\n";

            $inWeChatNotInInvoice = $transactions->diff($invoiceTransactionIds);
            echo '在微信对账单中，不在invoce中的有：',count($inWeChatNotInInvoice),"个\n";
            echo implode(',',$inWeChatNotInInvoice->toArray()), "\n";
            // diff with $transactions
            // dd($transactions);

        } catch (\Exception $e) {
            echo $e->getMessage()."\n";
            \Log::error($e);
            return false;
        }
    }
}
