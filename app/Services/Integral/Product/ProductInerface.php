<?php
namespace App\Services\Integral\Product;

interface ProductInerface
{
    public function select();

    public function createOrUpdate();

    public function delete();

    public function edit();
}