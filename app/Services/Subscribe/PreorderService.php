<?php namespace App\Services\Subscribe;

use App\Repositories\Subscribe\Station\StationRepositoryContract;
use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use Carbon\Carbon;

/**
 * Class Access
 * @package App\Services\Access
 */
class PreorderService
{

    protected $stationRepo;

    protected $preorderRepo;

    protected $staffService;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(StationRepositoryContract $stationRepo, PreorderRepositoryContract $preorderRepo, StaffService $staffService)
    {
        $this->stationRepo = $stationRepo;
        $this->preorderRepo = $preorderRepo;
        $this->staffService = $staffService;
    }

    public function getRecentlyStation($longitude, $latitude)
    {
        $station = $$this->stationRepo->Paginated(0);
        $data = [];
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
//        $preorder = $this->preorderRepo->update($input, $preorder_id);
        //更新相关星期字段信息
        $this->staffService->updateStaffWeekly($preorder_id, $is_delete);
    }
}
