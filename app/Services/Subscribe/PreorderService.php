<?php namespace App\Services\Subscribe;

use App\Repositories\Station\StationRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Carbon\Carbon;
use App\Repositories\Subscribe\StaffWeekly\StaffWeeklyRepositoryContract;
use App\Repositories\Subscribe\PreorderOrder\PreorderOrderRepositoryContract;
use App\Models\Subscribe\PreorderOrderProducts;
use App\Models\Billing\PreorderBilling;
use App\Services\Subscribe\SubscribeProtocol;
use App\Services\Client\Account\WalletService;
use App\Services\Billing\PreorderBillingService;
use App\Services\Billing\ChargeBillingService;
use App\Services\Billing\BillingProtocol;
use Log;

class PreorderService
{

    protected $stationRepo;

    protected $preorderRepo;

    protected $staffService;

    protected $staffWeeklyRepo;

    protected $preorderOrderRepo;

    protected $walletService;
    protected $preorderOrderBilling;

    protected $preorderBilling;


    public function __construct(StationRepositoryContract $stationRepo, PreorderRepositoryContract $preorderRepo,
                                StaffService $staffService, StaffWeeklyRepositoryContract $staffWeeklyRepo, ChargeBillingService $preorderBilling,
                                PreorderOrderRepositoryContract $preorderOrderRepo, WalletService $walletService, PreorderBillingService $preorderOrderBilling)
    {
        $this->stationRepo = $stationRepo;
        $this->preorderRepo = $preorderRepo;
        $this->staffService = $staffService;
        $this->staffWeeklyRepo = $staffWeeklyRepo;
        $this->preorderOrderRepo = $preorderOrderRepo;
        $this->walletService = $walletService;
        $this->preorderOrderBilling = $preorderOrderBilling;
        $this->preorderBilling = $preorderBilling;
    }

    public function getRecentlyStation($longitude, $latitude, $district_id)
    {
        $data = [];
        $station = $this->stationRepo->Paginated(0, [['field' => 'district_id', 'value' => $district_id, 'compare_type' => '=']]);
        if (empty($station->toArray())) {
            return $data;
        }
        foreach ($station as $value) {
            $distance = $this->getDistance($longitude, $latitude, display_coordinate($value['longitude']), display_coordinate($value['latitude']));
            $data[$distance] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'distance' => $distance,
            ];
        }
        ksort($data);
        $return = head($data);
        return $return;
    }

    /**
     * @desc 根据两点间的经纬度计算距离
     * @param float $lat 纬度值
     * @param float $lng 经度值
     * @return float
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        /*
          Convert these degrees to radians
          to work with the formula
        */

        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;

        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;

        /*
          Using the
          Haversine formula

          http://en.wikipedia.org/wiki/Haversine_formula

          calculate the distance
        */

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }

    public function updateStatus($input, $preorder_id)
    {
        $is_delete = false;
        if ($input['status'] == PreorderProtocol::STATUS_OF_PAUSE) {
            $input['pause_time'] = Carbon::now();
            $is_delete = true;
        } elseif ($input['status'] == PreorderProtocol::STATUS_OF_NORMAL) {
            $input['restart_time'] = Carbon::now();
        }
        $preorder = $this->preorderRepo->update($input, $preorder_id);
        //更新相关星期字段信息

        $this->staffService->updateStaffWeekly($preorder_id, $is_delete);
        return $preorder;
    }

    //所有订奶订单结算今天的配送金额
    public function settle()
    {
        $dt = Carbon::now();
        $week_of_year = $dt->weekOfYear;
        $day_of_week = $dt->dayOfWeek;
        $tomorrow_day_of_week = $day_of_week == 6 ? 0 : $day_of_week + 1;
        $week_name = PreorderProtocol::weekName($day_of_week);
        $tomorrow_week_name = PreorderProtocol::weekName($tomorrow_day_of_week);
        $weeklys = $this->staffWeeklyRepo->getOneDayDelivery($week_of_year, $week_name, $tomorrow_week_name);

        foreach ($weeklys as $weekly) {
            $day_data = $weekly->$week_name;
            $amount = 0;
            $tomorrow_day_data = $weekly->$tomorrow_week_name;
            $tomorrow_amount = 0;
            $preorder_order_product_sku = [];
            if (!empty($day_data)) {
                foreach ($day_data['sku'] as $sku) {
                    $amount += $sku['count'] * $sku['price'];
                    $preorder_order_product_sku[] = new PreorderOrderProducts([
                        'sku_id' => $sku['sku_id'],
                        'name' => $sku['sku_name'],
                        'count' => $sku['count'],
                        'price' => $sku['price'],
                    ]);
                }
                if (!empty($tomorrow_day_data)) {
                    foreach ($tomorrow_day_data['sku'] as $sku) {
                        $tomorrow_amount += $sku['count'] * $sku['price'];
                    }
                }
                $preorder_order_data = [
                    'preorder_id' => $weekly->preorder_id,
                    'record_no' => uniqid('rec_'),
                    'amount' => $amount,
                    'pay_at' => Carbon::now(),
                    'deliver_at' => Carbon::now(),
                    'status' => 0,
                ];
                $preorderOrder = $this->preorderOrderRepo->create($preorder_order_data);
                if (!empty($preorder_order_product_sku)) {
                    $preorderOrder->product()->saveMany($preorder_order_product_sku);
                }


                //扣除钱包金额
                $user_id = $this->preorderRepo->byId($weekly->preorder_id)->user_id;

                $order_billings_status = SubscribeProtocol::PRE_ORDER_BILLINGS_STATUS_NOT_PAID;
                $walletService = $this->walletService->setPayer($user_id);
                if ($walletService->enough($amount)) {
                    $order_billings_status = SubscribeProtocol::PRE_ORDER_BILLINGS_STATUS_OF_PAID;
                };

                //当天的扣款金额不足或者明天的扣款金额不足,暂停该订奶订单
                if (!$walletService->enough($amount) || !$walletService->enough($amount + $tomorrow_amount)) {
                    $this->updateStatus(['status' => PreorderProtocol::STATUS_OF_PAUSE, 'charge_status' => PreorderProtocol::STATUS_OF_NOT_ENOUGH], $weekly['preorder_id']);
                }

                //生成preorder_order_billings记录
                $preorder_order_billing = $this->preorderOrderBilling->create($amount, $order_billings_status);
                //wallet支付
                try {
                    $walletService->pay($preorder_order_billing);
                } catch (\Exception $e) {
                    Log::error('settle account message:' . $e->getMessage() . ' preorder_id:' . $weekly->preorder_id);
                }
            }
        }
        return 1;
    }

    public function payConfirm($charge_billing_id)
    {
        $charge_billing = $this->preorderBilling->setID($charge_billing_id);
        $charge_billing->setPaid(BillingProtocol::BILLING_CHANNEL_OF_PREORDER_BILLING);
        $user_id = $charge_billing->getPayer();
        $walletService = $this->walletService->setPayer($user_id);
        $walletService->recharge($charge_billing);
        $dt = Carbon::now();
        $day_of_week = $dt->dayOfWeek;
        $tomorrow_day_of_week = $day_of_week == 6 ? 0 : $day_of_week + 1;
        $preorder = $this->preorderRepo->byUserId($user_id);
        $preorder->load(['product' => function ($query) use ($tomorrow_day_of_week) {
            $query->where('weekday', $tomorrow_day_of_week);
        }, 'product.sku']);
        $tomorrow_amount = 0;
        if ($preorder->status == PreorderProtocol::STATUS_OF_PAUSE && $preorder->charge_status = PreorderProtocol::STATUS_OF_NOT_ENOUGH) {
            if ($preorder->product) {
                $product = $preorder->product->toArray();
                if (!empty($product[0])) {
                    foreach ($product[0]['sku'] as $sku) {
                        $tomorrow_amount += $sku['count'] * $sku['price'];
                    }
                    if ($charge_billing->amount > $tomorrow_amount) {
                        $this->updateStatus(['status' => PreorderProtocol::STATUS_OF_NORMAL, 'charge_status' => PreorderProtocol::STATUS_OF_ENOUGH], $preorder->id);
                    }
                }

            }
        }
        return 1;
    }
}
