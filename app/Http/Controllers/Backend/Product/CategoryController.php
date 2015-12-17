<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = $this->api->get('api/admin/categories/');
            return view('backend.categories.index');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function create()
    {
        return view('backend.categories.create');
    }
}
