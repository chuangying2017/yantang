<?php namespace App\Services\Subscribe;

use App\Repositories\Subscribe\Statements\StatementsRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;

class StatementsService
{


    protected $statementsRepo;

    protected $stationRepo;

    public function __construct(StatementsRepositoryContract $statementsRepo, StationRepositoryContract $stationRepo)
    {
        $this->statementsRepo = $statementsRepo;
        $this->stationRepo = $stationRepo;
    }

    public function create()
    {
        
    }

}
