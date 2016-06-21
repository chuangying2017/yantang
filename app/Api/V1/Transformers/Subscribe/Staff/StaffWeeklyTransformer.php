<?php namespace App\Api\V1\Transformers\Subscribe\Staff;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\StaffWeekly;
use App\Services\Subscribe\PreorderProtocol;

class StaffWeeklyTransformer extends TransformerAbstract
{

    public function transform(StaffWeekly $weekly)
    {
        $data = [];
        $order_am_count = 0;
        $order_pm_count = 0;
        $bottle_am_count = 0;
        $bottle_pm_count = 0;
        $week_name = $weekly->week_name;
        $sku_count = [];
        $address = [];

        foreach ($weekly->$week_name as $value) {
            if (!isset($weekly->daytime)) {
                if ($value->daytime == PreorderProtocol::DAYTIME_OF_AM) {
                    $order_am_count++;
                }
                if ($value->daytime == PreorderProtocol::DAYTIME_OF_PM) {
                    $order_pm_count++;
                }
            } else {
                $address[] = [
                    'address' => $value->address,
                    'sku' => $value->sku,
                ];
            }

            if (!empty($value->sku)) {
                foreach ($value->sku as $sku) {
                    if (!isset($weekly->daytime)) {
                        if ($value->daytime == PreorderProtocol::DAYTIME_OF_AM) {
                            $bottle_am_count += $sku->count;
                        }
                        if ($value->daytime == PreorderProtocol::DAYTIME_OF_PM) {
                            $bottle_pm_count += $sku->count;
                        }
                    } else {
                        if (!empty($sku_count) && array_key_exists($sku->sku_name, $sku_count)) {
                            $sku_count[$sku->sku_name] += $sku->count;
                        } else {
                            $sku_count[$sku->sku_name] = $sku->count;
                        }
                    }
                }
            }
        }

        if (!isset($weekly->daytime)) {
            $data['order_am_count'] = $order_am_count;
            $data['order_pm_count'] = $order_pm_count;
            $data['bottle_am_count'] = $bottle_am_count;
            $data['bottle_pm_count'] = $bottle_pm_count;
        } else {
            $data['sku_count'] = $sku_count;
            $data['address'] = $address;
        }
        return $data;
    }

}
