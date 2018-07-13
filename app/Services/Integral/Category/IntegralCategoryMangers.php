<?php
namespace App\Services\Integral\Category;

Interface IntegralCategoryMangers{

public function CreateOrUpdate($id=null, $data);

public function delete($id);

public function select();

}