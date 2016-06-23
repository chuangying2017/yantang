<?php namespace App\Repositories\Subscribe\Station;

use App\Models\Subscribe\Station;
use App\Http\Traits\EloquentRepository;
use App\Services\Subscribe\PreorderProtocol;

class EloquentStationRepository implements StationRepositoryContract
{

    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\Station';
    }

    public function getByUserId($user_id)
    {
        return Station::where('user_id', $user_id)->first();
    }

    public function preorder($user_id, $type, $pre_page)
    {
        if (empty($user_id)) {
            return false;
        }
        switch ($type) {
            case PreorderProtocol::STATUS_OF_UNTREATED:
                $query = Station::with(['preorder' => function ($query) {
                    $query->where('status', '=', PreorderProtocol::STATUS_OF_UNTREATED)->with('user');
                }]);
                break;
            case PreorderProtocol::STATUS_OF_NO_STAFF:
                $query = Station::with(['preorder' => function ($query) {
                    $query->where('status', '=', PreorderProtocol::STATUS_OF_NO_STAFF)->with('user');
                }]);
                break;
            case PreorderProtocol::STATUS_OF_NORMAL:
                $query = Station::with(['preorder' => function ($query) {
                    $query->where('status', '=', PreorderProtocol::STATUS_OF_NORMAL)->with('user');
                }]);
                break;
            case PreorderProtocol::STATUS_OF_NOT_ENOUGH:
                $query = Station::with(['preorder' => function ($query) {
                    $query->where('charge_status', '=', PreorderProtocol::STATUS_OF_NOT_ENOUGH)->with('user');
                }]);
                break;
            default:
                $query = Station::query();
                break;
        }
        $query = $query->where('status', '!=', PreorderProtocol::STATUS_OF_REJECT);
        $query = $query->where('user_id', $user_id);

        if (!empty($pre_page)) {
            $query = $query->paginate($pre_page);
        } else {
            $query = $query->get();
        }

        return $query;
    }

    public function bindStation($station_id, $user_id)
    {
        $station = Station::findOrFail($station_id);
        $station->user_id = $user_id;
        $station->save();
        return $station;
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
        return Station::find($id)->fill($input)->save();
    }

    public function show($id)
    {
        try {
            return Station::findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('该服务部不存在');
        }
    }

    public function weekly($user_id, $week_of_year)
    {
        $query = Station::where('user_id', $user_id)->with(['weekly' => function ($query) use ($week_of_year) {
            $query->where('week_of_year', $week_of_year);
        }])->first();
        return $query;
    }

    public function allStationBillings($begin_time, $end_time)
    {
        $query = Station::with('preorderOrder')->with('preorderOrder.product')->with('preorderOrder.orderBillings')
            ->whereHas('preorderOrder.orderBillings', function ($query) use ($begin_time, $end_time) {
                $query->where('created_at', '>=', $begin_time)->where('created_at', '<=', $end_time);
            })->get();
        return $query;
    }

    public function SearchInfo($keyword, $district_id, $per_page)
    {
        $query = Station::query();
        if (!empty($keyword)) {
            $query = Station::where(function ($query) use ($keyword) {
                $query->where('director', 'like', '%' . $keyword . '%')->orwhere('name', 'like', '%' . $keyword . '%')
                    ->orwhere('phone', $keyword)->orwhere('tel', $keyword);
            });
        }
        if (!empty($district_id)) {
            $query = $query->where('district_id', $district_id);
        }
        if (!empty($per_page)) {
            $query = $query->paginate($per_page);
        } else {
            $query = $query->get();
        }

        return $query;
    }

}