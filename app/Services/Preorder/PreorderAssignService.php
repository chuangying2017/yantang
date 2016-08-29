<?php namespace App\Services\Preorder;

use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;

class PreorderAssignService implements PreorderAssignServiceContact {

    /**
     * @var StationRepositoryContract
     */
    private $stationRepo;
    /**
     * @var PreorderAssignRepositoryContract
     */
    private $preorderAssignRepo;


    /**
     * PreorderAssignService constructor.
     * @param StationRepositoryContract $stationRepo
     */
    public function __construct(StationRepositoryContract $stationRepo, PreorderAssignRepositoryContract $preorderAssignRepo)
    {
        $this->stationRepo = $stationRepo;
        $this->preorderAssignRepo = $preorderAssignRepo;
    }

    /**
     * 查找订单服务部
     * @param $longitude
     * @param $latitude
     * @param $district_id
     * @return null / Station
     */
    public function assign($longitude, $latitude, $district_id)
    {

        $stations = $this->stationRepo->getByDistrict($district_id);
        if (!count($stations)) {
            return null;
        }

        /**
         * 顺时针遍服务范围点,若点在服务范围点向量左边则不在服务范围内
         */
        $available_stations = $this->filterAvailable($longitude, $latitude, $stations);

        if (!count($available_stations)) {
            $stations = $this->stationRepo->getByDistrict();
            $available_stations = $this->filterAvailable($longitude, $latitude, $stations);
            if (!count($available_stations)) {
                return null;
            }
        }

        if (count($available_stations) == 1) {
            return $available_stations[0];
        }

        return $this->findClosest($available_stations);
    }


//    public function inSide($longitude, $latitude, $geo)
//    {
//        $geo_count = count($geo);
//
//        if (!$geo_count) {
//            return false;
//        }
//
//
//        for ($point_index = 0; $point_index < $geo_count; $point_index++) {
//            $start_point = $geo[$point_index];
//            $end_point = (($point_index + 1) == $geo_count) ? $geo[0] : $geo[$point_index + 1];
//            $cal = bccomp(
//                bcmul(
//                    bcsub($this->getPointLongitude($start_point), $longitude, 6),
//                    bcsub($this->getPointLatitude($end_point), $latitude, 6),
//                    12),
//                bcmul(
//                    bcsub($this->getPointLatitude($start_point), $latitude, 6),
//                    bcsub($this->getPointLongitude($end_point), $longitude, 6),
//                    12),
//                12);
//
//            if ($cal === -1) {
//                return false;
//            }
//        }
//
//        return true;
//    }

    public function inSide($longitude, $latitude, $geo)
    {
        $x = $longitude;
        $y = $latitude;

        $inside = false;

        for ($i = 0, $j = count($geo) - 1; $i < count($geo); $j = $i++) {
            $xi = $geo[$i][0];
            $yi = $geo[$i][1];
            $xj = $geo[$j][0];
            $yj = $geo[$j][1];

            $intersect = (($yi > $y) != ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) $inside = !$inside;
        }

        return $inside;
    }

    private function getPointLongitude($point)
    {
        return $point[0];
    }

    private function getPointLatitude($point)
    {
        return $point[1];
    }

    /**
     * @param $longitude
     * @param $latitude
     * @param $stations
     * @return array
     */
    protected function filterAvailable($longitude, $latitude, $stations)
    {
        $available_stations = [];
        foreach ($stations as $station) {
            foreach (explode($station['geo'], ',') as $geo) {
                if ($this->inSide($longitude, $latitude, $geo)) {
                    $available_stations[] = $station;
                }
            }
        }
        return $available_stations;
    }

    /**
     * @param $available_stations
     * @return null
     */
    protected function findClosest($available_stations)
    {
        $shortest = null;
        $closest_station = null;
        foreach ($available_stations as $station) {
            $length = pow($station['longitude'] - $station['longitude'], 2) + pow($station['latitude'] - $station['latitude'], 2);
            if (!$shortest) {
                $shortest = $length;
                $closest_station = $station;
            } else if ($shortest > $length) {
                $shortest = $length;
                $closest_station = $station;
            }
        }
        return $closest_station;
    }


}
