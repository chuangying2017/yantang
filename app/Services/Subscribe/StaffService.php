<?php namespace App\Services\Subscribe;

use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use App\Repositories\Subscribe\StaffWeekly\StaffWeeklyRepositoryContract;
use App\Repositories\Subscribe\StaffPreorder\StaffPreorderRepositoryContract;
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

    public function __construct(StaffPreorderRepositoryContract $staffPreorderRepo, PreorderRepositoryContract $preorderRepo, StaffWeeklyRepositoryContract $staffWeeklyRepo)
    {
        $this->staffPreorderRepo = $staffPreorderRepo;
        $this->preorderRepo = $preorderRepo;
        $this->staffWeeklyRepo = $staffWeeklyRepo;
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
        foreach ($preorder->product as $product) {
            if (array_key_exists($product->weekday, $week_array)) {
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
        return $this->staffWeeklyRepo->updateByOther($week_of_year, $preorder_id, $preorder->staffPreorder->staff->id, $data);
    }

}
