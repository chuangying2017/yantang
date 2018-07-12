<?php
namespace App\Services\Integral\InterfaceFile;

Interface IntegralCategory{

public function CreateOrUpdate($id=null, $data);

public function delete($id);

public function select();

}