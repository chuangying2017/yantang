<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Services\Utilities\DataHelper;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends BackendController
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

        $stat_data = [
            'total_user_count' => DataHelper::totalUserCount(),
            'today_user_count' => DataHelper::todayUserCount(),
            'total_deal_amount' => display_price(DataHelper::totalDealAmount()),
            'today_deal_amount' => display_price(DataHelper::todayDealAmount()),
        ];
        return view('backend.dashboard', [
            'products' => $products,
            'orders' => $records,
            'delivers' => $delivers,
            'stat_data' => $stat_data
        ]);
    }
}
