<?php
namespace App\Services\Product\Brand;

/*
 * Created by PhpStorm.*
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:00 PM
 */
use App\Models\Brand;
use Pheanstalk\Exception;

/**
 * Class BrandRepository
 * @package App\Services\Product\Brand
 */
class BrandRepository
{
    /**
     * @param $name
     * @param null $cover_image
     * @return string|static
     */
    public static function create($name, $cover_image = null)
    {
        try {
            if (Brand::where('name', $name)->get()) {
                throw new Exception('BRAND EXISTED');
            } else {
                $brand = Brand::create([
                    'name' => $name,
                    'cover_image' => $cover_image
                ]);
            }
            return $brand;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function show($id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                throw new Exception('BRAND NOT FOUND');
            }
            return $brand;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param $data
     * @return int|string
     */
    public static function update($id, $data)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                throw new Exception('BRAND NOT FOUND');
            }
            $data = array_only($data, ['name', 'cover_image']);
            $brand->update($data);
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function delete($id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                throw new Exception('BRAND NOT FOUND');
            }
            /**
             * detach category
             */
            $brand->categories()->detach();
            $brand->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
