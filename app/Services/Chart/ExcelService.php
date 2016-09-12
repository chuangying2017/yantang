<?php namespace App\Services\Chart;

use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Excel;

class ExcelService {

    use InvoiceExcelTrait;

    public static function getFile($file, $local = false)
    {
        if ($local) {
            if (file_exists(storage_path('app/' . $file))) {
                return storage_path('app/' . $file);
            }
            return false;
        }

        $qiniu = \Storage::drive('qiniu');
        if ($qiniu->exists($file)) {
            return $qiniu->downloadUrl($file);
        }

        return false;
    }

    public static function saveAndDownload($e_data, $title, $path, $local = false)
    {
        $full_file_name = $path . $title . '.xls';
        $local_path = storage_path('app/' . $path);

        Excel::create($title, function ($excel) use ($e_data) {
            $excel->sheet('data', function ($sheet) use ($e_data) {
                $sheet->fromArray($e_data);
            });
        })->store('xls', $local_path);

        $local_full_path = storage_path('app/') . $full_file_name;
        $result = \Storage::drive('qiniu')->put($full_file_name, file_get_contents($local_full_path));

        if ($local) {
            return $local_full_path;
        }
        return self::getFile($full_file_name);
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

    public static function downPreorder($preorders, $title = null)
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
            $e_data['配送开始时间'] = $preorder['start_time'];
            $e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price(array_get($preorder, 'order.total_amount'));
            $e_data['优惠金额'] = display_price(array_get($preorder, 'order.discount_amount') ?: 0);
            $e_data['实付价格'] = display_price(array_get($preorder, 'order.pay_amount'));
            $e_data['商品详情'] = self::getPreorderSkusArray($preorder['skus']);

            $e_datas[$key] = $e_data;
        }

        $title = $title ?: '燕塘优鲜达订奶订单 - 导出时间:' . Carbon::now()->toDateTimeString();

        return self::downExcel($e_datas, $title);
    }


}
