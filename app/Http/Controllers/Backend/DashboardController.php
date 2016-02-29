<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $delivers = $this->api->get('api/admin/orders?status=paid')->count();
        $records = $this->api->get('api/admin/orders');
        $products = $this->api->get('api/admin/products');
        $products->setPath('/admin/products');
        return view('backend.dashboard', [
            'products' => $products,
            'orders' => $records,
            'delivers' => $delivers
        ]);
    }
}
