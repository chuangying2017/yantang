<?php namespace App\Repositories\Station;


use App\Models\Subscribe\Station;

interface StationRepositoryContract {

    public function createStation($station_data);

    public function updateStation($station_id, $station_data);

    public function bindUser($station_id, $user_id);

    public function unbindUser($station_id, $user_id);

    public function unbindAllUser($station_id);

    public function updateAsActive($station_ids);

    public function updateAsUnActive($station_ids);

    public function deleteStation($station_id);

    public function getStation($station_id, $with_user = true);

    public function getBindToken($station_id);

    public function getStationByUser($user_id);

    public function getStationIdByUser($user_id);

    public function getAll($only_id = false);

	/**
     * @param bool $only_id
     * @return Station
     */
    public function getAllActive($only_id = false);

    public function getByDistrict($district = null);
}
