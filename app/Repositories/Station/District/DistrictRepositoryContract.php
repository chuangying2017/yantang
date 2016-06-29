<?php namespace App\Repositories\Station\District;

interface DistrictRepositoryContract {

    public function getAll();

    public function create($name);

    public function update($id, $name);

    public function delete($id);

    public function get($id);

    public function increase($id, $count);

    public function decrease($id, $count);


}
