<?php namespace App\Repositories\Station\District;

interface DistrictRepositoryContract {

    public function getAll();

    public function create($name);

    public function delete($id);

    public function get($id);

}
