<?php namespace App\Services\Subscribe;

use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use App\Repositories\Subscribe\StaffWeekly\StaffWeeklyRepositoryContract;
use App\Repositories\Subscribe\StaffPreorder\StaffPreorderRepositoryContract;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Services\Subscribe\PreorderProtocol;
use Carbon\Carbon;

/**
 * Class Access
 * @package App\Services\Access
 */
class StaffService
{

    private $preorderRepo;

    private $staffPreorderRepo;

    private $staffWeeklyRepo;

    private $staffRepo;

    public function __construct(StaffPreorderRepositoryContract $staffPreorderRepo, PreorderRepositoryContract $preorderRepo, StaffWeeklyRepositoryContract $staffWeeklyRepo, StaffRepositoryContract $staffRepo)
    {
        $this->staffPreorderRepo = $staffPreorderRepo;
        $this->preorderRepo = $preorderRepo;
        $this->staffWeeklyRepo = $staffWeeklyRepo;
        $this->staffRepo = $staffRepo;
    }

    public function assign($input)
    {
        $staffPreorder = $this->staffPreorderRepo->create($input);
        $this->addStaffWeekly($input['preorder_id'], $input['staff_id']);
        $staffPreorder->load('preorder');
        //preorder status 更新为 normal
        $this->preorderRepo->update(['status' => 'normal'], $input['preorder_id']);
        return $staffPreorder;
    }

    public function addStaffWeekly($preorder_id, $staff_id)
    {
        $preorder = $this->preorderRepo->byId($preorder_id, ['product', 'product.sku', 'station', 'staffPreorder.staff']);
        if (!$preorder->product) {
            throw new \Exception('订单未配置,请先配置再分配配送员');
        }
        $data = [];
        foreach ($preorder->product as $product) {
            $data[PreorderProtocol::weekName($product->weekday)] = json_encode([
                'daytime' => $product->daytime,
                'station' => $preorder->station->name,
                'phone' => $preorder->phone,
                'address' => $preorder->address,
                'staff' => $preorder->staffPreorder->staff->name,
                'sku' => $product->sku,
            ]);
        }
        $data['staff_id'] = $staff_id;
        $data['week_of_year'] = Carbon::parse(Carbon::now())->weekOfYear;
        $this->staffWeeklyRepo->create($data);
    }

    public function  updateStaffWeekly($preorder_id = null, $is_delete = false)
    {
        $dt = Carbon::parse(Carbon::now());
        $week_of_year = $dt->weekOfYear;
        $day_of_week = $dt->dayOfWeek;
        $preorder = $this->preorderRepo->byId($preorder_id, ['staffPreorder.staff']);
        if ($is_delete) {
            return $this->staffWeeklyRepo->pause($week_of_year, $preorder_id, $preorder->staffPreorder->staff->id, $day_of_week);
        }
        $week_array = PreorderProtocol::weekPauseName($day_of_week);
        $preorder->load('product', 'product.sku', 'station');
        $data = [];
        $update_week_array = [];
        foreach ($preorder->product as $product) {
            if (array_key_exists($product->weekday, $week_array)) {
                $update_week_array[] = $week_array[$product->weekday];
                $data[$week_array[$product->weekday]] = json_encode([
                    'daytime' => $product->daytime,
                    'station' => $preorder->station->name,
                    'phone' => $preorder->phone,
                    'address' => $preorder->address,
                    'staff' => $preorder->staffPreorder->staff->name,
                    'sku' => $product->sku,
                ]);
            }
        }
        //其余的置空
        $other_weekday = array_diff($week_array, $update_week_array);
        foreach ($other_weekday as $value) {
            $data[$value] = json_encode([]);
        }
        return $this->staffWeeklyRepo->updateByOther($week_of_year, $preorder_id, $preorder->staffPreorder->staff->id, $data);
    }

    public function weeklyForStaff($query_day, $daytime = null)
    {
        $user_id = access()->id();
        $dt = Carbon::parse($query_day);
        $week_of_year = $dt->weekOfYear;
        $day_of_week = $dt->dayOfWeek;
        $staff = $this->staffRepo->byUserId($user_id);
        $week_name = PreorderProtocol::weekName($day_of_week);
        $weeklys = $this->staffWeeklyRepo->byStaffId($staff->id, $week_of_year, $week_name);
        $weeklys->week_name = $week_name;
        return $this->dataWeeklys($weeklys, $week_name, $daytime);
    }

    public function dataWeeklys($weeklys, $week_name, $daytime = null)
    {
        $data = [];
        $order_am_count = 0;
        $order_pm_count = 0;
        $bottle_am_count = 0;
        $bottle_pm_count = 0;
        $sku_count = [];
        $address = [];
        foreach ($weeklys as $weekly) {
            $day_data = $weekly->$week_name;
            if (is_null($daytime)) {
                if ($day_data['daytime'] == PreorderProtocol::DAYTIME_OF_AM) {
                    $order_am_count++;
                }
                if ($day_data['daytime'] == PreorderProtocol::DAYTIME_OF_PM) {
                    $order_pm_count++;
                }
            } else {
                $address[] = [
                    'address' => $day_data['address'],
                    'preorder_id' => $weekly['preorder_id'],
                    'sku' => $day_data['sku'],
                ];
            }

            if (!empty($day_data['sku'])) {
                foreach ($day_data['sku'] as $sku) {
                    if (is_null($daytime)) {
                        if ($day_data['daytime'] == PreorderProtocol::DAYTIME_OF_AM) {
                            $bottle_am_count += $sku['count'];
                        }
                        if ($day_data['daytime'] == PreorderProtocol::DAYTIME_OF_PM) {
                            $bottle_pm_count += $sku['count'];
                        }
                    } else {
                        if (!empty($sku_count) && array_key_exists($sku['sku_name'], $sku_count)) {
                            $sku_count[$sku['sku_name']] += $sku['count'];
                        } else {
                            $sku_count[$sku['sku_name']] = $sku['count'];
                        }
                    }
                }
            }
        }

        if (is_null($daytime)) {
            $data['order_am_count'] = $order_am_count;
            $data['bottle_am_count'] = $bottle_am_count;
            $data['order_pm_count'] = $order_pm_count;
            $data['bottle_pm_count'] = $bottle_pm_count;
        } else {
            $data['sku_count'] = $sku_count;
            $data['address'] = $address;
        }
        return $data;
    }

}
