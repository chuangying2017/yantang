<?php namespace App\Services\Subscribe;

use App\Repositories\Subscribe\Statements\StatementsRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Carbon\Carbon;
use App\Services\Subscribe\SubscribeProtocol;
use App\Models\Subscribe\StatementsProduct;

class StatementsService
{


    protected $statementsRepo;

    protected $stationRepo;

    public function __construct(StatementsRepositoryContract $statementsRepo, StationRepositoryContract $stationRepo)
    {
        $this->statementsRepo = $statementsRepo;
        $this->stationRepo = $stationRepo;
    }

    public function create($input)
    {
        $begin_time = '2016-06-08';
        $end_time = '2016-06-30';
        $dt = Carbon::parse($begin_time);
        $year = $dt->year;
        $month = $dt->month;
        $service_rate = 0.05;
        $stations = $this->stationRepo->allStationBillings($begin_time, $end_time);
        $settle_amount = 0;
        $service_amount = 0;
        foreach ($stations as $station) {
            if ($station->preorderOrder) {
                foreach ($station->preorderOrder as $preorder_order) {
//                    dd($preorder_order);
                    if ($preorder_order->orderBillings) {
                        foreach ($preorder_order->orderBillings as $order_billing) {
                            $amount = (float)display_price($order_billing->amount);
                            $settle_amount += $amount;
                            $service_amount += $amount * $service_rate;
                        }
                    }
                    if ($preorder_order->product) {
                        foreach ($preorder_order->product as $product) {
                            $statements_products[] = new StatementsProduct([
                                'name' => $product->name,
                                'settle_price' => $product->price,
                                'service_fee' => $service_rate,
                                'quantity' => $product->count,
                            ]);
                        }
                    }
                }
                $statement_data = [
                    'station_id' => $station->id,
                    'statement_no' => uniqid('sta_'),
                    'year' => $year,
                    'month' => $month,
                    'settle_amount' => $settle_amount,
                    'service_amount' => $service_amount,
                    'status' => SubscribeProtocol::STATEMENTS_STATUS_OF_PENDING,
                    'created_at' => Carbon::now(),
                ];
                $statements = $this->statementsRepo->create($statement_data);
                if (!empty($statements_products)) {
                    $statements->product()->saveMany($statements_products);
                }
            }
        }
    }

}
