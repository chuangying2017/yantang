<?php namespace App\Services\Subscribe;


/**
 * Class Access
 * @package App\Services\Access
 */
class PreorderService
{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getRecentlyStation($longitude, $latitude)
    {
        $station = $this->app->make('App\Repositories\Subscribe\Station\StationRepositoryContract')->Paginated(0);
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
}
