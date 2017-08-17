<?php namespace App\Repositories\Residence;

use App\Models\Residence;
use App\Repositories\Backend\AccessProtocol;
use DB;

class EloquentResidenceRepository implements ResidenceRepositoryContract {

    public function createResidence($station_data)
    {
        return Residence::create([
            'name' => $station_data['name'],
            'merchant_no' => $station_data['merchant_no'],
            'district_id' => $station_data['district_id'],
            'desc' => array_get($station_data, 'desc', ''),
            'tel' => array_get($station_data, 'tel', ''),
            'address' => $station_data['address'],
            'cover_image' => $station_data['cover_image'],
            'director' => $station_data['director'],
            'phone' => $station_data['phone'],
            'longitude' => $station_data['longitude'],
            'latitude' => $station_data['latitude'],
            'geo' => $station_data['geo'],
            'active' => 1
        ]);
    }

    public function updateResidence($station_id, $station_data)
    {
        $station = $this->getResidence($station_id);
        $station->fill(array_only($station_data, [
            'name',
            'district_id',
            'merchant_no',
            'tel',
            'address',
            'cover_image',
            'director',
            'phone',
            'longitude',
            'latitude',
            'active',
            'geo'
        ]));
        $station->save();

        return $station;
    }

    public function deleteResidence($station_id)
    {
        if (Preorder::query()->where('station_id', $station_id)->first()) {
            throw new \Exception('服务部存在订单,无法删除');
        }

        $this->unbindAllUser($station_id);

        return Residence::destroy($station_id);
    }

    public function getResidence($station_id, $with_user = true)
    {
        if ($with_user) {
            return Residence::with('user', 'user.client')->find($station_id);
        }
        return Residence::query()->find($station_id);
    }

    public function getAll($only_id = false)
    {
        if ($only_id) {
            return Residence::query()->pluck('id')->all();
        }
        return Residence::query()->get();
    }

    public function getAllActive($only_id = false)
    {
        #todo 缓存;

        if ($only_id) {
            return Residence::query()->where('active', 1)->pluck('id')->all();
        }
        return Residence::query()->where('active', 1)->get();
    }

    public function getByDistrict($district_id = null)
    {
        $query = Residence::query();

        if (!is_null($district_id)) {
            $query->where('district_id', $district_id);
        }

        return $query->where('active', 1)->get(['id', 'name', 'longitude', 'latitude', 'geo']);
    }


}
