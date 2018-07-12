<?php
namespace App\Services\Integral\Category;

use App\Services\Integral\InterfaceFile\IntegralCategory;
use \App\Models\Integral\IntegralCategory as IntegralModel;

class Category implements IntegralCategory
{

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return IntegralModel::destory($id);
    }

    /**
     * @param null $where
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function select($where=null)
    {
        return IntegralModel::query()->when($where,function($query)use ($where){
            $query->where($where);
        })->get();
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
        ]);
    }

    public function CreateOrUpdate($id = null, $data)
    {

        $cateModel = new IntegralModel();

        if($id)$cateModel = $cateModel::find($id);

        return $cateModel->fill($this->array_onlyData($data))->save();

    }
}