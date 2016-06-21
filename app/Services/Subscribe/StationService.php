<?php namespace App\Services\Subscribe;

use App\Services\Subscribe\PreorderProtocol;
use Carbon\Carbon;
use App\Repositories\Subscribe\Station\StationRepositoryContract;

/**
 * Class Access
 * @package App\Services\Access
 */
class StationService
{

    private $stationRepo;

    public function __construct(StationRepositoryContract $stationRepo)
    {
        $this->stationRepo = $stationRepo;
    }


    public function claimGoods($query_day)
    {
        $user_id = access()->id();
        $dt = Carbon::parse($query_day);
        $week_of_year = $dt->weekOfYear;
        $day_of_week = $dt->dayOfWeek;
        $week_name = PreorderProtocol::weekName($day_of_week);
        $station = $this->stationRepo->weekly($user_id, $week_of_year);
//        dd($station->toArray(), $week_name);
        return $this->claimGoodsData($station, $week_name);
    }

    public function claimGoodsData($station, $week_name)
    {
        $sku_count = [];
        foreach ($station->weekly as $weekly) {
            if (!empty($weekly->$week_name['sku'])) {
                foreach ($weekly->$week_name['sku'] as $sku) {
                    if (!empty($sku_count) && array_key_exists($sku['sku_name'], $sku_count)) {
                        $sku_count[$sku['sku_name']] += $sku['count'];
                    } else {
                        $sku_count[$sku['sku_name']] = $sku['count'];
                    }
                }
            }
        }
        return $sku_count;
    }
}
