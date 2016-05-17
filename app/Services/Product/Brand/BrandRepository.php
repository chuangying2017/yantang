<?php
namespace App\Services\Product\Brand;

/*
 * Created by PhpStorm.*
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:00 PM
 */
use App\Models\Product\Brand;
use Exception;

/**
 * Class BrandRepository
 * @package App\Services\Product\Brand
 */
class BrandRepository {

    /**
     * @param $name
     * @param null $cover_image
     * @return string|static
     */
    public static function create($name, $cover_image)
    {
        if (Brand::where('name', $name)->count()) {
            throw new Exception('BRAND EXISTED');
        }

        $brand = Brand::updateOrCreate(
            ['name' => $name],
            [
                'name'        => $name,
                'cover_image' => $cover_image
            ]
        );

        return $brand;
    }

    /**
     * @param $id
     * @return string
     */
    public static function show($id, $exception = true)
    {
        if (is_numeric($id)) {
            $brand = Brand::find($id);
        } else {
            $brand = Brand::where('name', $id)->first();
        }

        if ( ! $brand && $exception) {
            throw new Exception('BRAND NOT FOUND');
        }

        return $brand;
    }

    /**
     * @param $id
     * @param $data
     * @return resource|string
     */
    public static function update($id, $data)
    {
        $brand = Brand::find($id);
        if ( ! $brand) {
            throw new Exception('BRAND NOT FOUND');
        }

        $data = array_only($data, ['name', 'cover_image']);
        $brand->update($data);

        return $brand;

    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        $brand = Brand::find($id);
        if ( ! $brand) {
            throw new Exception('BRAND NOT FOUND');
        }
        /**
         * detach category
         */
        $brand->categories()->detach();
        $brand->delete();

        return true;
    }
}
