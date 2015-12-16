<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class ProductController extends Controller {

    public function index()
    {
        $products = $this->api->get('api/admin/products');

        return view('backend.product.index', compact('products'));
    }

    public function create()
    {
        return view('backend.product.create');
    }
}
