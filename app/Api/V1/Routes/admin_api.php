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
            });
        });

        $api->group(['namespace' => 'Product'], function ($api) {
            $api->group(['prefix' => 'products'], function ($api) {
                $api->resource('attributes', 'AttributeController');
                $api->resource('attributes.values', 'AttributeValueController');
                $api->resource('brands', 'BrandController');
                $api->resource('groups', 'BrandController');
                $api->resource('cats', 'CategoryController');
                $api->resource('mix-skus', 'ProductMixController', ['only' => ['index']]);
                $api->resource('/', 'ProductController');
            });
        });


        /**
         * Mall Orders
         */
        $api->group(['namespace' => 'Order'], function ($api) {
            $api->group(['prefix' => 'mall'], function ($api) {
                $api->resource('orders', 'MallOrderController', ['only' => ['index', 'show', 'update']]);
            });

            $api->group(['prefix' => 'special'], function ($api) {
                $api->resource('orders', 'SpecialOrderController', ['only' => ['index', 'show', 'update']]);
            });
        });

    });


    $api->group(['namespace' => 'Campaign'], function ($api) {
        $api->resource('store', 'StoreController');
        $api->resource('campaigns', 'CampaignController');
    });


    /**
     * 总部管理服务部
     */
    $api->group(['namespace' => 'Station'], function ($api) {
        $api->resource('stations', 'StationController');
    });


    /**
     * 通用接口
     */
    $api->get('images/token', 'Image\ImageController@token');
    $api->get('images', 'Image\ImageController@index');

});
