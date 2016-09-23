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
            });
        });


        //优惠信息
        $api->group(['namespace' => 'Promotion', 'prefix' => 'promotions'], function ($api) {
            $api->resource('coupons', 'CouponController');
            $api->resource('tickets', 'TicketController', ['only' => ['store']]);
            $api->resource('campaigns', 'CampaignController');
        });

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
            $api->resource('stations', 'StationInvoiceController', ['only' => ['index', 'show']]);
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
                $api->resource('origin-orders', 'SubscribeOrderController', ['only' => ['index', 'destroy']]);
            });
        });

        //管理服务部
        $api->group(['namespace' => 'Station'], function ($api) {
            $api->put('stations/{station_id}/unbind', 'StationController@unbind');
            $api->resource('stations', 'StationController');
            $api->resource('districts', 'DistrictController');
        });

    });


    /**
     * 通用接口
     */
    $api->get('images/token', 'Image\ImageController@token');
    $api->get('images', 'Image\ImageController@index');

});