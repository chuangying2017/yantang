<?php namespace App\Services\Chart;

use App\Repositories\Invoice\InvoiceProtocol;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;

trait InvoiceExcelTrait {

    public static function downloadStationInvoice($invoice, $local = true)
    {
        $path = self::getStationInvoiceLocalPath($invoice['invoice_date']);
        $title = $invoice['merchant_name'] . '-' . $invoice['invoice_date'];

        $full_file_name = $path . $title . '.xls';

        if (self::getFile($full_file_name, $local)) {
            return self::downloadLocalFile(self::getFile($full_file_name, $local));
        }

        $invoice->load('orders');
        $e_preorders = [];
        foreach ($invoice['orders'] as $key => $preorder) {
            $e_data['序号'] = $key+1;
            $e_data['接单日期'] = Carbon::parse($preorder['confirm_at'])->toDateString();
            $e_data['接单时间'] = Carbon::parse($preorder['confirm_at'])->toTimeString();
            $e_data['服务部'] = $preorder['station_name'];
            $e_data['配送员'] = $preorder['staff_name'];
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['姓名'] = $preorder['name'];
            $e_data['电话'] = $preorder['phone'];
            $e_data['地址'] = $preorder['address'];
            $e_data['商品详情'] = self::getPreorderSkusArray(json_decode($preorder['detail'], true));
            $e_data['下单时间'] = $preorder['order_at'];
            $e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price(array_get($preorder, 'total_amount'));
            $e_data['促销费'] = display_price(array_get($preorder, 'discount_amount') ?: 0);
            $e_data['客户实付金额'] = display_price(array_get($preorder, 'pay_amount'));
            $e_data['手续费'] = display_price(array_get($preorder, 'service_amount'));
            $e_data['实收价格'] = display_price(array_get($preorder, 'receive_amount'));

            $e_preorders[$key] = $e_data;
        }

        $e_preorders['合计'] = [
            '序号' => '合计',
            '接单日期' => '',
            '接单时间' => '',
            '服务部' => '',
            '配送员' => '',
            '订单号' => '',
            '姓名' => '',
            '电话' => '',
            '地址' => '',
            '商品详情' => '',
            '下单时间' => '',
            '配送状态' => '',
            '订单总价' => display_price(array_get($invoice, 'total_amount')),
            '促销费' => display_price(array_get($invoice, 'discount_amount') ?: 0),
            '客户实付金额' => display_price(array_get($invoice, 'pay_amount')),
            '手续费' => display_price(array_get($invoice, 'service_amount')),
            '实收价格' => display_price(array_get($invoice, 'receive_amount')),
        ];


        $invoice->load('collect_orders');
        $collect_invoice_data = [
            'total_amount' => 0,
            'discount_amount' => 0,
            'pay_amount' => 0,
            'service_amount' => 0,
            'receive_amount' => 0,
        ];
        $e_collects = [];
        foreach ($invoice['collect_orders'] as $key => $collect_order) {
            $e_collect['序号'] = $key+1;
            $e_collect['支付日期'] = Carbon::parse($collect_order['pay_at'])->toDateString();
            $e_collect['支付时间'] = Carbon::parse($collect_order['pay_at'])->toTimeString();
            $e_collect['服务部'] = $collect_order['station_name'];
            $e_collect['配送员'] = $collect_order['staff_name'];
            $e_collect['订单号'] = $collect_order['order_no'];
            $e_collect['姓名'] = $collect_order['name'];
            $e_collect['电话'] = $collect_order['phone'];
            $e_collect['地址'] = $collect_order['address'];
            $e_collect['商品详情'] = self::getCollectOrderSku(json_decode($collect_order['detail'], true));
            $e_collect['订单总价'] = display_price($collect_order['total_amount']);
            $e_collect['促销费'] = display_price($collect_order['discount_amount']) ?: 0;
            $e_collect['客户实付金额'] = display_price($collect_order['pay_amount']);
            $e_collect['手续费'] = display_price($collect_order['service_amount']);
            $e_collect['实收价格'] = display_price($collect_order['receive_amount']);


            $collect_invoice_data['total_amount'] += $collect_order['total_amount'];
            $collect_invoice_data['discount_amount'] += $collect_order['discount_amount'];
            $collect_invoice_data['pay_amount'] += $collect_order['pay_amount'];
            $collect_invoice_data['service_amount'] += $collect_order['service_amount'];
            $collect_invoice_data['receive_amount'] += $collect_order['receive_amount'];

            $e_collects[$key] = $e_collect;
        }


        $e_collects['合计'] = [
            '序号' => '合计',
            '支付日期' => '',
            '支付时间' => '',
            '服务部' => '',
            '配送员' => '',
            '订单号' => '',
            '姓名' => '',
            '电话' => '',
            '地址' => '',
            '商品详情' => '',
            '订单总价' => display_price($collect_invoice_data['total_amount']),
            '促销费' => display_price($collect_invoice_data['discount_amount']),
            '客户实付金额' => display_price($collect_invoice_data['pay_amount']),
            '手续费' => display_price($collect_invoice_data['service_amount']),
            '实收价格' => display_price($collect_invoice_data['receive_amount']),
        ];

        $e_preorders['合计']['订单总价'] -= $e_collects['合计']['订单总价'];
        $e_preorders['合计']['促销费'] -= $e_collects['合计']['促销费'];
        $e_preorders['合计']['客户实付金额'] -= $e_collects['合计']['客户实付金额'];
        $e_preorders['合计']['手续费'] -= $e_collects['合计']['手续费'];
        $e_preorders['合计']['实收价格'] -= $e_collects['合计']['实收价格'];

        $e_data = [
            '商城订单' => $e_preorders,
            '线下收款' => $e_collects,
        ];

        $full_file_name = $path . $title . '.xls';
        $local_path = storage_path('app/' . $path);

        self::save($e_data, $title, $local_path);
        return self::download($full_file_name, $local);
    }

    public static function downloadStationAdminInvoice($invoice, $title = '燕塘优先达服务部对账单-概况-', $local = true)
    {
        $title .= $invoice['invoice_date'];
        $path = self::getStationInvoiceLocalPath($invoice['invoice_date']);
        $full_file_name = $path . $title . '.xls';

       // if (self::getFile($full_file_name, $local)) {
       //     return self::downloadLocalFile(self::getFile($full_file_name, $local));
       // }

        $e_datas = [];
        foreach ($invoice->detail as $key => $merchant_invoice) {
            $e_data['序号'] = $key+1;
            $e_data['接单时间'] = $merchant_invoice['start_time'] . ' - ' . $merchant_invoice['end_time'];
            $e_data['服务部'] = $merchant_invoice['merchant_name'];
            $e_data['订单数'] = $merchant_invoice['total_count'];
            $e_data['订单总价'] = display_price(array_get($merchant_invoice, 'total_amount'));
            $e_data['促销费'] = display_price(array_get($merchant_invoice, 'discount_amount') ?: 0);
            $e_data['客户实付金额'] = display_price(array_get($merchant_invoice, 'pay_amount'));
            $e_data['手续费'] = display_price(array_get($merchant_invoice, 'service_amount'));
            $e_data['实收价格'] = display_price(array_get($merchant_invoice, 'receive_amount'));
            $e_data['对账情况'] = InvoiceProtocol::statusName($merchant_invoice['status']);
            $e_data['备注'] = $merchant_invoice['memo'];

            $e_datas[$key] = $e_data;
        }

        $summary = [
            '序号' => '合计',
            '接单时间' => '',
            '服务部' => '',
            '订单数' => '',
            '订单总价' => display_price(array_get($invoice, 'total_amount')),
            '促销费' => display_price(array_get($invoice, 'discount_amount') ?: 0),
            '客户实付金额' => display_price(array_get($invoice, 'pay_amount')),
            '手续费' => display_price(array_get($invoice, 'service_amount')),
            '实收价格' => display_price(array_get($invoice, 'receive_amount')),
        ];

        $e_datas['合计'] = $summary;

        return self::saveAndDownload($e_datas, $title, $path, $local);
    }

    public static function downloadStationAdminInvoiceDetail($invoice, $local = true)
    {
        $pack_file = '燕塘优先达服务部对账单-明细-' . $invoice['invoice_date'] . '.zip';
        $path = self::getStationInvoiceLocalPath($invoice['invoice_date']);

        $full_file_name = $path . $pack_file;

        if (self::getFile($full_file_name, $local)) {
            return self::downloadLocalFile(self::getFile($full_file_name, $local));
        }

        $invoice_urls = [];
        foreach ($invoice->detail as $key => $merchant_invoice) {
            $invoice_urls[] = self::downloadStationInvoice($merchant_invoice, true)->getFile()->getPathname();
        }

        $zip = new \ZipArchive;
        $zip->open(storage_path('app/' . $full_file_name), \ZipArchive::CREATE);
        foreach ($invoice_urls as $file) {
            $zip->addFile($file, substr($file, strlen(storage_path('app/' . $path))));
        }
        $zip->close();

        return self::downloadLocalFile(self::getFile($full_file_name, $local));
    }

    protected static function getStationInvoiceLocalPath($date)
    {
        return 'invoices/stations/' . $date . '/';
    }

}
