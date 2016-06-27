<?php namespace App\Repositories\Station;

use App\Models\Subscribe\Station;
use App\Repositories\Statement\MerchantRepositoryContract;
use DB;

class EloquentStationRepository implements StationRepositoryContract, MerchantRepositoryContract {

    public function createStation($station_data)
    {
        return Station::create([
            'name' => $station_data['name'],
            'district_id' => $station_data['district_id'],
            'desc' => array_get($station_data, 'desc', ''),
            'tel' => array_get($station_data, 'tel', ''),
            'address' => $station_data['address'],
            'cover_image' => $station_data['cover_image'],
            'director' => $station_data['director'],
            'phone' => $station_data['phone'],
            'longitude' => $station_data['longitude'],
            'latitude' => $station_data['latitude'],
            'active' => 1
        ]);
    }

    public function updateStation($station_id, $station_data)
    {
        $station = $this->getStation($station_id);
        $station->fill(array_only($station_data, [
            'name',
            'district_id',
            'tel',
            'address',
            'cover_image',
            'director',
            'phone',
            'longitude',
            'latitude',
            'active'
        ]));
        $station->save();

        return $station;
    }

    public function bindUser($station_id, $user_id)
    {
        $user_relate = \DB::table('station_user')
            ->where('user_id', $user_id)
            ->first();
        if ($user_relate) {
            throw new \Exception('用户不能绑定多个服务部');
        }

        return \DB::table('station_user')->insert([
            'station_id' => $station_id,
            'user_id' => $user_id
        ]);
    }

    public function updateAsActive($station_ids)
    {
        return Station::whereIn('id', to_array($station_ids))->update(['active' => 1]);
    }

    public function updateAsUnActive($station_ids)
    {
        return Station::whereIn('id', to_array($station_ids))->update(['active' => 0]);
    }

    public function deleteStation($station_id)
    {
        return Station::destroy($station_id);
    }

    public function getStation($station_id, $with_user = true)
    {
        if ($with_user) {
            return Station::with('user')->find($station_id);
        }
        return Station::find($station_id);
    }

    public function getStationByUser($user_id)
    {
        $relate = \DB::table('station_user')
            ->where('user_id', $user_id)
            ->first();
        if (!$relate) {
            throw new \Exception('用户未绑定服务部', 403);
        }

        return $this->getStation($relate->station_id);
    }

    public function getAll($only_id = false)
    {
        if ($only_id) {
            return Station::query()->pluck('id')->all();
        }
        return Station::query()->get();
    }

    public function getAllActive($only_id = false)
    {
        #todo 缓存;

        if ($only_id) {
            return Station::query()->where('active', 1)->pluck('id')->all();
        }
        return Station::query()->where('active', 1)->get();
    }

    public function unbindUser($station_id, $user_id)
    {
        DB::table('station_user')->where('station_id', $station_id)->where('user_id', $user_id)->delete();
    }

    public function getStationIdByUser($user_id)
    {
        $station = $this->getStationByUser($user_id);

        return $station['id'];
    }

    public function getBindToken($station_id)
    {
        return generate_bind_token($station_id);
    }

}
