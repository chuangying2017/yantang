<?php
namespace App\Repositories\Integral\Supervisor;

interface ShareCarriageWheel extends Supervisor
{
    public function updateOrCreate(int $id = null,array $array);
}