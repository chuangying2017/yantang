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

        $stations = $this->stationRepo->getByDistrict($district_id);//从站点station查看所有的相同的district_id List table
        if (!count($stations)) {
            $stations = $this->stationRepo->getByDistrict();
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
    //查找站点距离 就近原则
    public function inSide($longitude, $latitude, $geo)
    {
        $x = $longitude;
        $y = $latitude;

        $inside = false;

        for ($i = 0, $j = count($geo) - 1; $i < count($geo); $j = $i++) {
            $xi = $geo[$i][0];//first 的经度
            $yi = $geo[$i][1];//first 的纬度
            $xj = $geo[$j][0];//tail 的经度
            $yj = $geo[$j][1];//tail 的纬度
            /*
             * 首先会计算站点所在的经纬度
             * 继续查出站点的所有经纬度
             * 然后实际上如果正确一个经纬度为true的话 continue 找
             * 原理来只要在一个经纬度的计算返回内为真就会退出 circulation
             * 但实际上这里是没有退出循环的
             * */
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

            $station_geo = $station['geo'];
            if (is_array(array_first(array_first($station_geo)))) {
                foreach ($station_geo as $geo) {
                    if ($this->inSide($longitude, $latitude, $geo)) {
                        $available_stations[] = $station;
                    }
                }
            } else {
                if ($this->inSide($longitude, $latitude, $station_geo)) {
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
