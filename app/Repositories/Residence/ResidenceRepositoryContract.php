<?php namespace App\Repositories\Residence;


interface ResidenceRepositoryContract {

    public function createResidence($residence_data);

    public function updateResidence($residence_id, $residence_data);

    public function deleteResidence($residence_id);

    public function getResidence($residence_id);

    public function getAll($only_id = false);
    public function getAllPaginated();
}
