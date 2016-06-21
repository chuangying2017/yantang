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
}