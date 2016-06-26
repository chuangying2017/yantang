<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\StationStaff;
use App\Services\Subscribe\PreorderProtocol;

class StaffsDataTransformer extends TransformerAbstract
{

    public function transform(StationStaff $staffs)
    {
        $data = [];

        if (isset($staffs->today) && $staffs->today) {
//            dd($staffs->daytime, isset($staffs->daytime) && is_null($staffs->daytime), is_null(0), isset($staffs->daytime));
            $order_am_count = 0;
            $order_pm_count = 0;
            $bottle_am_count = 0;
            $bottle_pm_count = 0;
            $sku_count = [];
            foreach ($staffs->preorders as $preorders) {
                if (!empty($preorders->product)) {
                    foreach ($preorders->product as $products) {
                        if (!isset($staffs->daytime)) {
                            if ($products->daytime == PreorderProtocol::DAYTIME_OF_AM) {
                                $order_am_count++;
                            }
                            if ($products->daytime == PreorderProtocol::DAYTIME_OF_PM) {
                                $order_pm_count++;
                            }
                        }
                        if (!empty($products->sku)) {
                            foreach ($products->sku as $skus) {
                                if (!isset($staffs->daytime)) {
                                    if ($products->daytime == PreorderProtocol::DAYTIME_OF_AM) {
                                        $bottle_am_count += $skus->count;
                                    }
                                    if ($products->daytime == PreorderProtocol::DAYTIME_OF_PM) {
                                        $bottle_pm_count += $skus->count;
                                    }
                                } else {
                                    if (!empty($sku_count) && array_key_exists($skus->sku_name, $sku_count)) {
                                        $sku_count[$skus->sku_name] += $skus->count;
                                    } else {
                                        $sku_count[$skus->sku_name] = $skus->count;
                                    }
                                }
                            }
                        }
                        if (!isset($staffs->daytime)) {
                            $data['order_am_count'] = $order_am_count;
                            $data['order_pm_count'] = $order_pm_count;
                            $data['bottle_am_count'] = $bottle_am_count;
                            $data['bottle_pm_count'] = $bottle_pm_count;
                        } else {
                            $data['sku_count'] = $sku_count;
                        }
                    }
                }
            }

        } else {

        }

        return $data;
    }

}
