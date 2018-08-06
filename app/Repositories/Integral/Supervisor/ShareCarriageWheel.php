<?php
namespace App\Repositories\Integral\Supervisor;

interface ShareCarriageWheel extends Supervisor
{
    public function updateOrCreate($id = null,array $array);
}