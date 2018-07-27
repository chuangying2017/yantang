<?php
namespace App\Repositories\Integral\Supervisor;

interface Supervisor
{
    public function get_all();

    public function find($where);

    public function create(array $array);

    public function update($id, array $array);

    public function edit($id,$content);

    public function delete($where);
}