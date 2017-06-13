<?php namespace App\Repositories\Client;

use App\Models\Client\Client;
use App\Models\Subscribe\Preorder;
use App\Services\Client\ClientProtocol;
use Illuminate\Support\Str;

class EloquentClientRepository implements ClientRepositoryContract {

    public function createClient($user_id, $extra_data)
    {
        return Client::query()->updateOrCreate(
            [
                'user_id' => $user_id
            ],
            [
                'user_id' => $user_id,
                'nickname' => array_get($extra_data, 'nickname', Str::random(8)),
                'avatar' => array_get($extra_data, 'avatar', ClientProtocol::DEFAULT_AVATAR),
                'sex' => array_get($extra_data, 'sex', ''),
            ]
        );
    }

    public function updateClient($user_id, $client_data)
    {
        return Client::query()->updateOrCreate(
            [
                'user_id' => $user_id
            ],
            [
                'user_id' => $user_id,
                'nickname' => array_get($client_data, 'nickname', Str::random(8)),
                'avatar' => array_get($client_data, 'avatar', ClientProtocol::DEFAULT_AVATAR),
                'birthday' => array_get($client_data, 'birthday', '1990-01-01'),
                'sex' => array_get($client_data, 'sex', ''),
            ]
        );
    }

    public function showClient($user_id, $with_user = false)
    {
        if ($with_user) {
            return Client::with('user')->find($user_id);
        }
        return Client::query()->find($user_id);
    }

    public function deleteClient($user_id)
    {
        return Client::query()->where('user_id', $user_id)->delete();
    }

    public function getAllClients($keyword = null, $status = ClientProtocol::STATUS_OK, $with_user = false, $order_by = 'created_at', $sort = 'desc')
    {
        return $this->queryClients($keyword, $status, $with_user, $order_by, $sort, null);
    }

    public function getClientsPaginated($keyword = null, $status = ClientProtocol::STATUS_OK, $with_user = false, $order_by = 'created_at', $sort = 'desc', $per_page = ClientProtocol::PER_PAGE)
    {
        return $this->queryClients($keyword, $status, $with_user, $order_by, $sort, $per_page);
    }

    protected function queryClients($keyword = null, $status = null, $with_user = false, $order_by = 'created_at', $sort = 'desc', $per_page = ClientProtocol::PER_PAGE)
    {
        $query = Client::query();

        if (!is_null($keyword)) {
            switch ($keyword) {
                case is_zh_phone($keyword):
                    $user_ids = Preorder::query()->where('phone', $keyword)->pluck('user_id')->all();
                    $query->whereIn('user_id', $user_ids);
                    break;
                default:
                    $query->where('nickname', 'like', '%' . $keyword . '%');
                    break;
            }
        }

        if ($with_user) {
            $query = $query->with('user');
        }
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy($order_by, $sort);

        if ($per_page) {
            return $query->paginate($per_page);
        }
        return $query->get();
    }

    public function getAllClientsByOrderNo( $order_nos ){
        $query = Client::query();

        if (!is_null($order_nos)) {
            if(!is_array($order_nos) ){
                $order_nos = [$order_nos];
            }
            $user_ids = Preorder::query()->whereIn('order_no', $order_nos)->pluck('user_id')->all();
            $query->whereIn('user_id', $user_ids);
        }

        return $query->with('user')->paginate(count($order_nos));

    }

    public function block($user_id)
    {
        return Client::where('user_id', $user_id)->update(['status' => ClientProtocol::STATUS_BLOCK]);
    }

    public function unblock($user_id)
    {
        return Client::where('user_id', $user_id)->update(['status' => ClientProtocol::STATUS_OK]);
    }


}
