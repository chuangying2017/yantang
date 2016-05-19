<?php namespace App\Repositories\Subscribe\Station;

use App\Models\Subscribe\Station;
use App\Http\Traits\EloquentRepository;

class EloquentStationRepository implements StationRepositoryContract
{
    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\Station';
    }

    public function getByUserId($user_id)
    {
        return Station::where('user_id', $user_id)->get();
    }

    public function bindStation($station_id, $user_id)
    {
        $station = Station::findOrFail($station_id);
        if (!empty($station->user_id)) {
            if ($station->user_id == $user_id) {
                throw \Exception('该服务部已经绑定,无须重新绑定');
            } else {
                throw \Exception('该服务部已经绑定其他人,绑定不成功');
            }
        }
        $station->user_id = $user_id;
    }

    public function create($input)
    {
        $input['status'] = empty($input['status']) ? 0 : $input['status'];
        $input['longitude'] = store_coordinate($input['longitude']);
        $input['latitude'] = store_coordinate($input['latitude']);
        return Station::create($input);
    }

    public function update($id, $input)
    {
        return Station::find('id', $id)->fill($input)->save();
    }

    public function show($id)
    {
        try {
            return Station::findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('该服务部不存在');
        }
    }

    public function destroy($id)
    {
        //todo 补充关联的删除
        $station = Station::findOrFail($id);
        $station->delete();
    }

}