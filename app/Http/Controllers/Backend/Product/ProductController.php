<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class ProductController extends Controller
{

    public function index()
    {
        try {
            $products = $this->api->get('api/admin/products/');
            return view('backend.product.index', compact('products'));
        } catch (\Exception $e) {
            return $e;
        }

    }

    public function create()
    {
        $categories = $this->api->get('api/admin/categories')['data'];
        $groups = $this->api->get('api/admin/groups');
        javascript()->put([
            'categories' => $categories,
            'groups' => $groups,
            'attributes' => [
                [
                    "name" => "尺寸",
                    "id" => 1,
                    "values" => []
                ],
                [
                    "name" => "颜色",
                    "id" => 2,
                    "values" => []
                ],
                [
                    "name" => "规格",
                    "id" => 3,
                    "values" => []
                ],
                [
                    "name" => "产地",
                    "id" => 4,
                    "values" => []
                ]
            ]
        ]);
        return view('backend.product.create');
    }
}
