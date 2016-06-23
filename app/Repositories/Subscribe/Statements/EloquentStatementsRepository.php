<?php namespace App\Repositories\Subscribe\Statements;

use App\Models\Subscribe\Statements;
use App\Http\Traits\EloquentRepository;

class EloquentStatementsRepository implements StatementsRepositoryContract
{

    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\Statements';
    }

    public function show($id)
    {
        $query = Statements::find($id);
        return $query;
    }

    public function byStationId($station_id, $year, $month = null)
    {
        $query = Statements::where('station_id', $station_id);
        $query = $query->where('year', $year);
        if (!is_null($month)) {
            $query = $query->where('month', $month)->with('product');
            return $query->first();
        }
        return $query->get();
    }

    public function info($per_page = null)
    {
        $query = Statements::orderBy('created_at', 'DESC');
        $query = $query->with(['product']);

        if (!empty($per_page)) {
            $query = $query->paginate($per_page);
        } else {
            $query = $query->get();
        }

        return $query;
    }
}