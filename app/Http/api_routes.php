<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {


    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => 'cors'], function ($api) {

        /**
         * 用户相关
         */
        require_once(__DIR__ . '/../Api/V1/Routes/user_api.php');

        /**
         * 商城
         */
        require_once(__DIR__ . '/../Api/V1/Routes/mall_api.php');

        /**
         * 优惠购
         */
        require_once(__DIR__ . '/../Api/V1/Routes/campaign_api.php');

        /**
         *
         * Admin
         *
         */
        require_once(__DIR__ . '/../Api/V1/Routes/admin_api.php');


        /**
         * Gateway
         */


        $api->group(['namespace' => 'Gateway'], function ($api) {

            $api->group(['prefix' => 'pingxx'], function ($api) {
                $api->post('paid', 'PingxxNotifyController@paid');
                $api->post('refund', 'PingxxNotifyController@refund');
                $api->post('transfer', 'PingxxNotifyController@transfer');
                $api->post('summary', 'PingxxNotifyController@summary');
            });

            $api->group(['prefix' => 'qiniu'], function ($api) {
                $api->post('callback', 'QiniuNotifyController@store')->name('qiniu.callback');
            });

        });

        /**
         * 订奶系统
         */
        $api->group(['namespace' => 'Subscribe'], function ($api) {
            //服务部端
            $api->group(['namespace' => 'Station', 'prefix' => 'stations'], function ($api) {
                $api->resource('products', 'ProductsController', ['only' => ['index']]); //查看可定购商品
                $api->get('bind_staff', 'StaffsController@bindStaff'); //绑定配送员
                $api->resource('staffs', 'StaffsController');
                $api->get('info', 'StationController@index'); //查看服务部信息
                $api->get('products', 'StationController@products'); //查看可定购商品
                $api->get('bind_station', 'StationController@bindStation'); //绑定服务部
                $api->get('claim_goods', 'StationController@claimGoods'); //取货单
                $api->resource('preorders', 'StationPreorderController'); //服务部下的订奶订单列表
                $api->get('statements', 'StatementsController@index'); //查看某年对账列表
                $api->post('account_check', 'StatementsController@accountCheck'); //对账
            });
            //订奶客户端
            $api->group(['namespace' => 'Preorder'], function ($api) {
                $api->get('subscribe/stations', 'PreorderController@stations');
                $api->get('subscribe/preorder_record', 'PreorderController@preorderRecord'); //获取送奶记录
                $api->resource('subscribe/preorders', 'PreorderController');
                $api->resource('subscribe/preorder_product', 'PreorderProductController');
                $api->get('subscribe/user_amount', 'TopUpController@userAmount'); //获取用户余额
                $api->get('subscribe/topup', 'TopUpController@rechargeRecord'); //获取充值记录
                $api->post('subscribe/pay_confirm', 'TopUpController@payConfirm');
                $api->resource('subscribe/topup', 'TopUpController');
            });
            //订奶配送端
            $api->group(['namespace' => 'Staff'], function ($api) {
                $api->get('staffs/preorders/data', 'StaffPreorderController@data');
                $api->resource('staffs/preorders', 'StaffPreorderController');
            });
        });

        /**
         * 总部管理服务部
         */
        $api->group(['namespace' => 'Admin\\Station', 'prefix' => 'admin'], function ($api) {
            $api->resource('stations', 'AdminStationController'); //查看服务部列表
        });

        $api->group(['namespace' => 'Admin\\Subcribe', 'prefix' => 'admin'], function ($api) {
            $api->get('allot_station', 'PreorderController@allotStation');
            $api->resource('preorders', 'PreorderController'); //后台查看订单列表
            $api->post('create_billing', 'AdminStatementsController@createBilling'); //生成对账单
            $api->resource('statements', 'AdminStatementsController'); //查看对账单
            $api->resource('recharge_amount', 'RechargeAmountController'); //后台查看设置充值金额
        });


        /**
         *
         * Station
         *
         */
        $api->group(['namespace' => 'Subscribe\Station', 'prefix' => 'stations'], function ($api) {
            $api->get('products', 'ProductController@index');
        });


        //区域
        $api->group(['namespace' => 'District'], function ($api) {
            $api->resource('district', 'DistrictController');
        });


    });

});
