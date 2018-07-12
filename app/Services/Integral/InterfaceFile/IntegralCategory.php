<?php
namespace App\Services\Integral\InterfaceFile;

Interface IntegralCategory{

public function create($data);

public function update($id, $data);

public function delete();

public function select();

}