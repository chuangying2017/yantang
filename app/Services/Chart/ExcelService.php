<?php namespace App\Services\Chart;

use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Excel;

class ExcelService {

    use InvoiceExcelTrait;

    public static function getFile($file, $local = false)
    {
        if ($local) {
            return self::getLocalFullPath($file);
        }

        return self::getCDNFullPath($file);
    }

    public static function saveAndDownload($e_data = null, $title, $path, $local = false)
    {
        $full_file_name = $path . $title . '.xls';
        $local_path = storage_path('app/' . $path);

        if ($e_data) {
            Excel::create($title, function ($excel) use ($e_data) {
                $excel->sheet('data', function ($sheet) use ($e_data) {
                    $sheet->fromArray($e_data);
                });
            })->store('xls', $local_path);
        }

        if ($local) {
            return self::downloadLocalFile(self::getFile($full_file_name, true));
        }

        $result = \Storage::drive('qiniu')->put($full_file_name, file_get_contents(self::getFile($full_file_name, true)));

        return self::getFile($full_file_name, $local);
    }

    public static function downExcel($e_data, $title)
    {
        return Excel::create($title, function ($excel) use ($e_data) {
            $excel->sheet('data', function ($sheet) use ($e_data) {
                $sheet->fromArray($e_data);
            });
        })->export('xls');
    }


    protected static function getPreorderSkusArray($skus)
    {
        $e_sku = '';
        foreach ($skus as $sku) {
            $e_sku .= $sku['name'] . ', ' . '总共: ' . $sku['total'] . ', ' . '剩余: ' . $sku['remain'] . "\n";
        }
        return $e_sku;
    }

    public static function downPreorder($preorders, $title = null, $expand_skus = false)
    {
        $e_datas = [];

        $preorders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain');
            },
            'station' => function ($query) {
                $query->select('id', 'name');
            }]);

        $preorders = $preorders->toArray();

        foreach ($preorders as $key => $preorder) {
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['姓名'] = $preorder['name'];
            $e_data['电话'] = $preorder['phone'];
            $e_data['地址'] = $preorder['address'];
            $e_data['服务部'] = array_get($preorder, 'station.name');
            $e_data['下单时间'] = $preorder['created_at'];
            $e_data['接单时间'] = $preorder['confirm_at'];
            $e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price($preorder['total_amount']);
            $e_data['优惠金额'] = display_price(array_get($preorder, 'order.discount_amount') ?: 0);
            $e_data['实付价格'] = display_price(array_get($preorder, 'order.pay_amount'));
            $e_data['商品详情'] = self::getPreorderSkusArray($preorder['skus']);

            if ($expand_skus) {
                foreach ($preorder['skus'] as $sku) {
                    $e_data['商品名称'] = $sku['name'];
                    $e_data['商品数量'] = $sku['total'];
                }
            }
            $e_datas[] = $e_data;
        }

        $title = $title ?: '燕塘优鲜达订奶订单 - 导出时间:' . Carbon::now()->toDateTimeString();
        return self::saveAndDownload($e_datas, $title, 'preorders/', true);
    }

    public static function downloadPreorderBounce($preorders, $title = null)
    {
        $e_datas = [];

        $preorders->load([
            'skus' => function ($query) {
                $query->select('product_sku_id', 'order_id', 'name', 'total');
            },
            'skus.sku' => function ($query) {
                $query->withTrashed()->select('id', 'price', 'settle_price');
            }
        ]);
        
        foreach ($preorders as $key => $preorder) {
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['下单时间'] = $preorder['created_at'];
            $e_data['接单时间'] = $preorder['confirm_at'];
            $e_data['订单总价'] = display_price($preorder['total_amount']);

            foreach ($preorder['skus'] as $sku) {
                $e_data['产品名'] = $sku['name'];
                $e_data['数量'] = $sku['total'];
                $e_data['原价'] = display_price($sku['sku']['price']);
                $e_data['出厂价'] = display_price($sku['sku']['settle_price']);
                $e_data['结算金额'] = display_price($sku['sku']['settle_price'] * $sku['total']);
                $e_data['提成金额'] = $e_data['结算金额'] * 0.04;
            }

            $e_datas[] = $e_data;
        }
        

        $title = $title ?: '燕塘优鲜达威臣销售提成-' . Carbon::now()->toDateTimeString();
        return self::saveAndDownload($e_datas, $title, 'bounce/', true);
    }


    protected static function getLocalFullPath($file)
    {
        if (file_exists(storage_path('app/' . $file))) {
            return storage_path('app/' . $file);
        }
        return false;
    }

    protected static function getCDNFullPath($file)
    {
        $qiniu = \Storage::drive('qiniu');
        if ($qiniu->exists($file)) {
            return $qiniu->downloadUrl($file);
        }

        return false;
    }

    public static function downloadLocalFile($file)
    {
        return response()->download($file);
    }


}
