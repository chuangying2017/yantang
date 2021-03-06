<?php namespace App\Repositories\Station;

use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\Station;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Comment\CommentProtocol;
use App\Repositories\Statement\MerchantRepositoryContract;
use DB;

class EloquentStationRepository implements StationRepositoryContract, MerchantRepositoryContract {

    public function createStation($station_data)
    {
        return Station::create([
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

    public function updateStation($station_id, $station_data)
    {
        $station = $this->getStation($station_id);
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

    public function bindUser($station_id, $user_id)
    {
        $user_relate = \DB::table('station_user')
            ->where('user_id', $user_id)
            ->first();
        if ($user_relate) {
            throw new \Exception('用户不能绑定多个服务部', 400);
        }

        \DB::table('station_user')->insert([
            'station_id' => $station_id,
            'user_id' => $user_id
        ]);

        access()->addRole(AccessProtocol::ROLE_OF_STATION);

        return true;
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
        if (Preorder::query()->where('station_id', $station_id)->first()) {
            throw new \Exception('服务部存在订单,无法删除');
        }

        $this->unbindAllUser($station_id);

        return Station::destroy($station_id);
    }

    public function getStation($station_id, $with_user = true)
    {
        if ($with_user) {
            return Station::with('user', 'user.client')->find($station_id);
        }
        return Station::query()->find($station_id);
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

        access()->removeRole(AccessProtocol::ROLE_OF_STATION, $user_id);

        return true;
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

    public function getByDistrict($district_id = null)
    {
        $query = Station::query();

        if (!is_null($district_id)) {
            $query->where('district_id', $district_id);
        }

        return $query->where('active', 1)->get(['id', 'name', 'longitude', 'latitude', 'geo']);
    }

    public function unbindAllUser($station_id)
    {
        $user_ids = DB::table('station_user')->where('station_id', $station_id)->pluck('user_id');

        foreach ($user_ids as $user_id) {
            $this->unbindUser($station_id, $user_id);
        }
    }

    public function getAllStaffDownDataComment($only_id = false,$staff_id = false)
    {
        switch ($only_id){
            case StationProtocol::SELECT_STATION_IS_STAFF:
                $result = Station::query()->with([$only_id=>function($query){
                    $query->where('status',StationProtocol::STATUS_OF_STAFF_BIND)
                          ->select(['id','station_id','name','phone','user_id','status']);
                }])->where('active','1')->get(['id','merchant_no','name','address','phone','tel','district_id']);
                break;
            case StationProtocol::SELECT_STATION_DOWN_STAFF_COMMENT:
                $result = Preorder::query()->where('staff_id',$staff_id)->with(['staff'=>function($query){
                    $query->select(['id','name','user_id','phone']);
                }])->whereHas('comments',function($query){
                    $query->where('comment_type',CommentProtocol::COMMENT_STATUS_IS_USES);
                })->get(['id','status','name','staff_id']);
                $result->load('comments');
                break;
            default:
                $result = Station::query()->get();
        }

        return $result;
    }

    public function getManyExpressionSelect($array_comment){

    }
}
