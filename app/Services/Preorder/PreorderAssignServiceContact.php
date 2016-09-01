<?php namespace App\Services\Preorder;

use App\Models\Subscribe\Station;

interface PreorderAssignServiceContact {

	/**
     * @param $longitude
     * @param $latitude
     * @param $district_id
     * @return Station|null
     */
    public function assign($longitude, $latitude, $district_id);

}
