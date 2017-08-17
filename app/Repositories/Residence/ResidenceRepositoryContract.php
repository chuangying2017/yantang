<?php namespace App\Repositories\Residence;


interface ResidenceRepositoryContract {

    public function createResidence($station_data);

    public function updateResidence($station_id, $station_data);

    public function deleteResidence($station_id);

    public function getResidence($station_id, $with_user = true);

    public function getAll($only_id = false);

	/**
     * @param bool $only_id
     * @return Residence
     */
    public function getAllActive($only_id = false);
}
