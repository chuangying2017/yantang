<?php
namespace App\Services\Integral\Category;

use App\Services\Integral\InterfaceFile\IntegralCategory;
use \App\Models\Integral\IntegralCategory as IntegralModel;
use Mockery\Exception;

class Category implements IntegralCategory
{

    public function create($data)
    {
        try{

            return IntegralModel::create($this->array_onlyData($data));

        }catch (Exception $exception){

            \Log::error($exception->getMessage());
        }

    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function select()
    {
        return IntegralModel::all();
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
}