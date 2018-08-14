<?php


/**
 *
 * Admin
 *
 */
$api->group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'api.auth'], function ($api) {

    $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_SUPERVISOR]], function ($api) {

        $api->group(['namespace' => 'Access', 'prefix' => 'access'], function ($api) {

            $api->resource('users', 'UserController');

            $api->get('users/deleted', 'UserController@deleted')->name('admin.access.users.deleted');

            /**
             * Specific User
             */
            $api->group(['prefix' => 'user/{id}', 'where' => ['id' => '[0-9]+']], function ($api) {
                $api->get('delete', 'UserController@delete')->name('admin.access.user.delete-permanently');
                $api->get('restore', 'UserController@restore')->name('admin.access.user.restore');
                $api->get('mark/{status}', 'UserController@mark')->name('admin.access.user.mark')->where(['status' => '[0,1,2]']);
                $api->post('password/change', 'UserController@updatePassword')->name('admin.access.user.change-password');
            });


            /**
             * Role Management
             */
            $api->group(['namespace' => 'Role'], function ($api) {
                $api->resource('roles', 'RoleController', ['except' => ['show', 'create', 'edit']]);
            });

            /**
             * Permission Management
             */
            $api->group(['prefix' => 'roles', 'namespace' => 'Permission'], function ($api) {
                $api->resource('permission-group', 'PermissionGroupController', ['except' => ['show', 'create', 'edit']]);
                $api->resource('permissions', 'PermissionController', ['except' => ['show', 'create', 'edit']]);

                $api->group(['prefix' => 'groups'], function ($api) {
                    $api->post('update-sort', 'PermissionGroupController@updateSort')->name('admin.access.roles.groups.update-sort');
                });
            });
        });

        $api->group(['namespace' => 'Client'], function ($api) {
            $api->group(['prefix' => 'clients'], function ($api) {
                $api->resource('groups.users', 'GroupUserController');
                $api->resource('groups', 'GroupController', ['except' => ['show', 'edit']]);
                $api->resource('members.users', 'MemberUserController');
                $api->resource('members', 'MemberController', ['except' => ['show', 'edit']]);
                $api->resource('users', 'UserController');
                $api->get('usersFetch','UserController@fetchUser');
            });
        });

        //优惠信息
        $api->group(['namespace' => 'Promotion', 'prefix' => 'promotions'], function ($api) {
            $api->resource('coupons', 'CouponController');
            $api->resource('tickets', 'TicketController', ['only' => ['store']]);
            $api->resource('campaigns', 'CampaignController');
            //红包
            $api->put('red-envelopes/{id}/active', 'RedEnvelopeController@active');
            $api->put('red-envelopes/{id}/unactive', 'RedEnvelopeController@unactive');
            $api->resource('red-envelopes', 'RedEnvelopeController', ['only' => ['index', 'show', 'update', 'store']]);

            $api->put('activities/{id}/active', 'ActivityController@active');
            $api->put('activities/{id}/unactive', 'ActivityController@unactive');
            $api->resource('activities', 'ActivityController', ['only' => ['index', 'show', 'update', 'store']]);

        });
        
        
/*        $api->group(['namespace' => 'Card'], function ($api) {
            $api->group(['prefix' => 'card'], function ($api) {
                $api->get('test', 'CategoriesController@test');

                $api->get('categories/index', 'CategoriesController@index');
                
                $api->post('categories/add','CategoriesController@store');

                $api->get('categories/{id}/edit','CategoriesController@edit');
                
                //$api->post('categories/update/{id}','CategoriesController@update');
                $api->post('categories/update','CategoriesController@update');

                $api->get('categories/{id}/del','CategoriesController@destroy');
                
                
                $api->get('cardorder/index', 'CardOrderController@index');
                $api->get('cardorder/usercard/{id}', 'CardOrderController@usercard');
                $api->get('cardorder/detail/{id}', 'CardOrderController@detail');
                
                
            });
        });*/
        
           

    });

    /**
     * 商城管理员
     */
    $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_MALL]], function ($api) {

        $api->group(['namespace' => 'Product'], function ($api) {
            $api->group(['prefix' => 'products'], function ($api) {
                $api->resource('attributes', 'AttributeController');
                $api->resource('attributes.values', 'AttributeValueController');
                $api->resource('brands', 'BrandController');
                $api->resource('groups', 'GroupController');
                $api->resource('cats', 'CategoryController');
                $api->get('mix-skus', 'ProductMixController@skus');
                $api->get('mix-products', 'ProductMixController@products');
                $api->put('{id}/up', 'ProductController@up');
                $api->put('{id}/down', 'ProductController@down');
            });
            $api->resource('products', 'ProductController');
        });

        $api->group(['prefix' => 'mall'], function ($api) {
            $api->resource('orders', 'Order\MallOrderController', ['only' => ['index', 'show', 'update']]);
            $api->resource('banner', 'Banner\BannerController');
        });

    });


    /**
     * 对账管理员
     */
    $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_FINANCE]], function ($api) {

        $api->group(['namespace' => 'Statement', 'prefix' => 'statements'], function ($api) {
            $api->resource('store', 'StoreStatementController', ['only' => ['index', 'show']]);
            $api->resource('stations', 'StationStatementController', ['only' => ['index', 'show']]);
        });

        $api->group(['namespace' => 'Invoice', 'prefix' => 'invoices'], function ($api) {
            $api->get('stations/{invoice_no}/orders', 'StationInvoiceController@orders')->name('admin.invoices.stations.orders');
            $api->get('stations/{invoice_no}/collect_orders', 'StationInvoiceController@collect_orders')->name('admin.invoices.stations.collect_orders');
            $api->resource('stations', 'StationInvoiceController', ['only' => ['index', 'show']]);
            $api->get('bonus', 'StationInvoiceController@bonus');
        });

        $api->group(['namespace'=>'Collect'], function( $api ){
            $api->resource('collect_order','CollectController',['only'=>['index','show']]);
        });
    });


    /**
     * 优惠购管理员
     */
    //团购
    $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STORE_ADMIN]], function ($api) {

        $api->group(['prefix' => 'special'], function ($api) {
            $api->resource('orders', 'Order\SpecialOrderController', ['only' => ['index', 'show', 'update']]);
        });

        $api->group(['namespace' => 'Campaign'], function ($api) {
            $api->resource('store', 'StoreController');
            $api->resource('campaigns', 'CampaignController');
        });

        $api->group(['namespace' => 'Subscribe'], function ($api) {
            $api->resource('preorders/comments', 'CommentController');
        });
    });


    /**
     * 服务部&订奶
     */
    $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STATION_ADMIN]], function ($api) {

        //订奶订单
        $api->group(['namespace' => 'Order'], function ($api) {
            $api->group(['prefix' => 'subscribe'], function ($api) {
                $api->resource('orders', 'PreorderController', ['only' => ['index', 'show', 'update']]);
                $api->resource('collect_orders', 'CollectOrderController', ['only' => ['index', 'show', 'update']]);
                $api->resource('origin-orders', 'SubscribeOrderController', ['only' => ['index', 'destroy']]);
                $api->get('origin-orders/refund', 'SubscribeOrderController@refundOrders');
                $api->put('origin-orders/refund/{refund_order_no}/reject', 'SubscribeOrderController@rejectRefund');
                $api->put('origin-orders/refund/{refund_order_no}/approve', 'SubscribeOrderController@approveRefund');
            });
        });

        //管理服务部
        $api->group(['namespace' => 'Station'], function ($api) {
            $api->put('stations/{station_id}/unbind', 'StationController@unbind');
            $api->put('stations/{station_id}/kpi', 'StationController@setKpi');
            $api->resource('stations', 'StationController');
            $api->resource('districts', 'DistrictController');
        });
        $api->get('residences/getDropdown', 'Residence\ResidenceController@getDropdown');
        $api->resource('residences', 'Residence\ResidenceController');

    });

    /**
     * 通用接口
     */
    $api->get('images/token', 'Image\ImageController@token');
    $api->get('images', 'Image\ImageController@index');
    
    /**
     * 评论
     * */
    $api->group(['middleware'=>'api.auth','access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STATION_ADMIN],function($api){
        $api->group(['namespace'=>'Comments','prefix'=>'comments'],function($api){
            $api->resource('AdminComments','OperationController');
            $api->get('stationShow','OperationController@show_station_and_staff')->name('station.staff');
        });
    });

    /**
     * 积分管理
     *
     * */
    $api->group(['middleware'=>'api.auth'], function($api){
        $api->group(['namespace'=>'Integral','prefix'=>'integral'],function($api){
            $api->resource('category','CategoryMangerController');
            $api->resource('specification','SpecificationController');
            $api->resource('product','ProductController');
            $api->get('product/{id}/edit','ProductController@edit');
            $api->get('freedomThe/shippingManagement','FreedomController@Shipping_management');
            $api->get('freedomThe/{id}/shippingOrderDetail','FreedomController@Shipping_order_detail')->where('id','\d+');
            $api->post('freedomThe/{order_id}/convert_confirm','FreedomController@convert_confirm')->where('order_id','\d+');
            $api->post('freedomThe/card_manager', 'FreedomController@card_manager');
            $api->resource('company','CompanyController');
            $api->get('freedomThe/integralCardShow','FreedomController@card_show');
            $api->get('freedomThe/IntegralCardFind/{card_id}','FreedomController@card_find');
            $api->post('freedomThe/{card_id}/card_update','FreedomController@card_update');
            $api->put('freedomThe/{card_id}/card_delete','FreedomController@card_destroy');
            $api->get('freedomThe/sign/signGet','FreedomController@sign_get');
            $api->put('freedom/sign/signUpdate','FreedomController@sign_update');
            $api->resource('exchangeThe','ExchangeController');
            $api->get('validityGet','FreedomController@integral_validity');
            $api->put('validity/{id}/Update','FreedomController@validity_update');
        });
    });
});



    

