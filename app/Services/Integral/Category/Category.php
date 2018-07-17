<?php
namespace App\Services\Integral\Category;

use \App\Models\Integral\IntegralCategory as IntegralModel;
use \App\Models\Integral\Specification;
class Category implements IntegralCategoryMangers
{

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return IntegralModel::destroy($id);
    }

    /**
     * @param null $where
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function select($where=null, $sort='asc', $sort_field='sort_type')
    {
        $integral = IntegralModel::query()->when($where,function($query)use ($where){
            $query->where($where);
        });

        return $integral->orderBy($sort_field, $sort)->get();
    }

    /**
     * @param array $data
     * @return array
     */
    protected function array_onlyData(array $data)
    {
        return array_only($data,[
            'title',
            'sort_type',
            'status',
            'cover_image',
            'type',
            'describe',
        ]);
    }

    public function CreateOrUpdate($id = null, $data, $model = 'IntegralModel')
    {

        $cateModel = $this->model_string($model);

        if($id)$cateModel = $cateModel::find($id);

        return $cateModel->fill($this->array_onlyData($data))->save();

    }

    public function model_string($string)
    {
        switch ($string)
        {
            case 'IntegralModel':
                return new IntegralModel();
                break;
            case 'Specification':
                return new Specification();
                break;
            default:
                throw new \Exception('model not exist',500);
        }
    }
}