<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Services\Utilities\DataHelper;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends BackendController {


    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $records = $this->api->get('api/admin/orders');
        $products = $this->api->get('api/admin/products');
        $products->setPath('/admin/products');

        $stat_data = [
            'total_user_count'  => DataHelper::totalUserCount(),
            'today_user_count'  => DataHelper::todayUserCount(),
            'total_deal_amount' => DataHelper::totalDealAmount(),
            'today_deal_amount' => DataHelper::todayDealAmount(),
        ];

        return view('backend.dashboard', [
            'products'  => $products,
            'orders'    => $records,
            'stat_data' => $stat_data
        ]);
    }
}
