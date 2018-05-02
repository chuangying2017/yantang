<?php namespace App\Repositories\Store;

use App\Models\Store;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Statement\MerchantRepositoryContract;
use DB;

class StoreRepository implements StoreRepositoryContract, MerchantRepositoryContract {

    public function createStore($store_data)
    {
        return Store::create([
            'name' => $store_data['name'],
            'address' => $store_data['address'],
            'cover_image' => $store_data['cover_image'],
            'director' => $store_data['director'],
            'phone' => $store_data['phone'],
            'longitude' => $store_data['longitude'],
            'latitude' => $store_data['latitude'],
            'active' => 1
        ]);
    }

    public function updateStore($store_id, $store_data)
    {
        $store = $this->getStore($store_id);
        $store->fill(array_only($store_data, [
            'name',
            'address',
            'cover_image',
            'director',
            'phone',
            'longitude',
            'latitude',
            'active'
        ]));
        $store->save();

        return $store;
    }

    public function bindUser($store_id, $user_id)
    {
        $user_relate = \DB::table('store_user')
            ->where('user_id', $user_id)
            ->first();
        if ($user_relate) {
            throw new \Exception('用户不能绑定多个商店');
        }

        \DB::table('store_user')->insert([
            'store_id' => $store_id,
            'user_id' => $user_id
        ]);

        access()->addRole(AccessProtocol::ROLE_OF_STORE);

        return true;

    }

    public function updateAsActive($store_ids)
    {
        return Store::whereIn('id', to_array($store_ids))->update(['active' => 1]);
    }

    public function updateAsUnActive($store_ids)
    {
        return Store::whereIn('id', to_array($store_ids))->update(['active' => 0]);
    }

    public function deleteStore($store_id)
    {
        return Store::destroy($store_id);
    }

    public function getStore($store_id, $with_user = true)
    {
        if ($with_user) {
            return Store::with('user')->find($store_id);
        }
        return Store::find($store_id);
    }

    public function getStoreByUser($user_id)
    {
        $relate = \DB::table('store_user')
            ->where('user_id', $user_id)
            ->first();
        if (!$relate) {
            throw new \Exception('用户未绑定店铺', 403);
        }

        return $this->getStore($relate->store_id);
    }

    public function getAll($only_ids = false)
    {
        if ($only_ids) {
            return Store::query()->pluck('id')->all();
        }
        return Store::query()->get();
    }

    public function getAllActive($only_ids = false)
    {
        if ($only_ids) {
            return Store::query()->where('active', 1)->pluck('id')->all();
        }
        return Store::query()->where('active', 1)->get();
    }

    public function unbindUser($store_id, $user_id)
    {
        DB::table('store_user')->where('store_id', $store_id)->where('user_id', $user_id)->delete();
        access()->removeRole(AccessProtocol::ROLE_OF_STORE);

        return true;
    }

    public function getStoreIdByUser($user_id)
    {
        $store = $this->getStoreByUser($user_id);

        return $store['id'];
    }

    public function getBindToken($store_id)
    {
        return generate_bind_token($store_id);
    }

}
