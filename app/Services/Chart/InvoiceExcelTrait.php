<?php namespace App\Services\Chart;

use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;

trait InvoiceExcelTrait {

    public static function downloadStationInvoice($invoice, $local = false)
    {
        $path = self::getStationInvoiceLocalPath($invoice['invoice_date']);
        $title = $invoice['merchant_name'];

        $full_file_name = $path . $title . '.xls';

        if (self::getFile($full_file_name, $local)) {
            return self::getFile($full_file_name, $local);
        }

        $invoice->load('orders');
        $e_datas = [];
        foreach ($invoice['orders'] as $key => $preorder) {
            $e_data['序号'] = $key;
            $e_data['接单日期'] = Carbon::parse($preorder['confirm_at'])->toDateString();
            $e_data['接单时间'] = Carbon::parse($preorder['confirm_at'])->toTimeString();
            $e_data['服务部'] = $preorder['station_name'];
            $e_data['配送员'] = $preorder['staff_name'];
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['姓名'] = $preorder['name'];
            $e_data['电话'] = $preorder['phone'];
            $e_data['地址'] = $preorder['address'];
            $e_data['商品详情'] = self::getPreorderSkusArray(json_decode($preorder['detail'], true));
            $e_data['下单时间'] = $preorder['created_at'];
            $e_data['配送开始时间'] = $preorder['start_time'];
            $e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price(array_get($preorder, 'total_amount'));
            $e_data['促销费'] = display_price(array_get($preorder, 'discount_amount') ?: 0);
            $e_data['客户实付金额'] = display_price(array_get($preorder, 'pay_amount'));
            $e_data['手续费'] = display_price(array_get($preorder, 'service_amount'));
            $e_data['实收价格'] = display_price(array_get($preorder, 'receive_amount'));

            $e_datas[$key] = $e_data;
        }

        $e_datas['合计'] = [
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
            '配送开始时间' => '',
            '配送状态' => '',
            '订单总价' => display_price(array_get($invoice, 'total_amount')),
            '促销费' => display_price(array_get($invoice, 'discount_amount') ?: 0),
            '客户实付金额' => display_price(array_get($invoice, 'pay_amount')),
            '手续费' => display_price(array_get($invoice, 'service_amount')),
            '实收价格' => display_price(array_get($invoice, 'receive_amount')),
        ];

        return self::saveAndDownload($e_datas, $title, $path, $local);
    }

    public static function downloadStationAdminInvoice($invoice, $title = '总部')
    {
        $path = self::getStationInvoiceLocalPath($invoice['invoice_date']);
        $full_file_name = $path . $title . '.xls';
        if (self::getFile($full_file_name)) {
            return self::getFile($full_file_name);
        }

        $e_datas = [];
        foreach ($invoice->detail as $key => $merchant_invoice) {
            $e_data['序号'] = $key;
            $e_data['接单时间'] = $merchant_invoice['start_time'] . ' - ' . $merchant_invoice['end_time'];
            $e_data['服务部'] = $merchant_invoice['merchant_name'];
            $e_data['订单数'] = $merchant_invoice['total_count'];
            $e_data['订单总价'] = display_price(array_get($merchant_invoice, 'total_amount'));
            $e_data['促销费'] = display_price(array_get($merchant_invoice, 'discount_amount') ?: 0);
            $e_data['客户实付金额'] = display_price(array_get($merchant_invoice, 'pay_amount'));
            $e_data['手续费'] = display_price(array_get($merchant_invoice, 'service_amount'));
            $e_data['实收价格'] = display_price(array_get($merchant_invoice, 'receive_amount'));

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

        return self::saveAndDownload($e_datas, $title, $path);
    }

    public static function downloadStationAdminInvoiceDetail($invoice)
    {
        $pack_file = $invoice['invoice_date'] . '-订单数据.zip';
        $path = self::getStationInvoiceLocalPath($invoice['invoice_date']);

        $full_file_name = $path . $pack_file;

        if (self::getFile($full_file_name)) {
            return self::getFile($full_file_name);
        }

        $invoice_urls = [];
        foreach ($invoice->detail as $key => $merchant_invoice) {
            $invoice_urls[] = self::downloadStationInvoice($merchant_invoice, true);
        }

        $zip = new \ZipArchive;
        $zip->open(storage_path('app/' . $full_file_name), \ZipArchive::CREATE);
        foreach ($invoice_urls as $file) {
            $zip->addFile($file, substr($file, strlen(storage_path('app/' . $path))));
        }
        $zip->close();

        $result = \Storage::drive('qiniu')->put($full_file_name, file_get_contents(storage_path('app/' . $full_file_name)));

        return \Storage::drive('qiniu')->get($full_file_name);
    }

    protected static function getStationInvoiceLocalPath($date)
    {
        return 'invoices/stations/' . $date . '/';
    }

}
