<?php
namespace App\Repositories\Integral\ShareCarriageWheel;

use App\Repositories\Integral\Supervisor\ShareCarriageWheel;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

abstract class ShareAccessRepositories implements ShareCarriageWheel
{
    protected $model;

    protected $array = [];

    abstract protected function init();


    public function __construct()
    {
        $this->init();
    }

    public function updateOrCreate(int $id = null, array $array)
    {
        $model = $this->model;
       if (is_integer($id))
       {
        $model = $model->find($id);
       }

        $model->fill(array_only($array,$this->array));

        return $model->save();
    }

    public function get_all($paginate = 20)
    {
        $model = $this->model;

       if($paginate)
       {
           return $model->paginate($paginate);
       }

       return $model->get();
    }

    public function find($where)
    {
        if(is_array($where))
        {
            $fetch = $this->model->where($where)->first();
        }elseif (is_integer($where))
        {
            $fetch = $this->model->find($where);
        }
        else
        {
            throw new Exception('类型没有被找到',422);
        }

        return $fetch;
    }

    public function create(array $array)
    {

    }

    public function update($id, array $array)
    {
        // TODO: Implement update() method.
    }

    public function edit($id, $content)
    {
        // TODO: Implement edit() method.
    }

    public function delete($where)
    {
      return  $this->model->destory($where);
    }

    protected function set_model(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}