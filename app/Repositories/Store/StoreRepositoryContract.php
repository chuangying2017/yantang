<?php namespace App\Repositories\Store;

interface StoreRepositoryContract {

    public function createStore($store_data);

    public function updateStore($store_id, $store_data);

    public function bindUser($store_id, $user_id);

    public function unbindUser($store_id, $user_id);

    public function updateAsActive($store_ids);

    public function updateAsUnActive($store_ids);

    public function deleteStore($store_id);

    public function getStore($store_id, $with_user = true);

    public function getBindUrl($store_id);

    public function getStoreByUser($user_id);

    public function getStoreIdByUser($user_id);

    public function getAll();

    public function getAllActive();
}
