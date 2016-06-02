<?php namespace App\Services\Subscribe;

use App\Repositories\Subscribe\Staff\StaffRepositoryContract;

/**
 * Class Access
 * @package App\Services\Access
 */
class StaffService
{

    private $staffRepo;

    private $staffPreorderRepo;

    public function __construct(StaffRepositoryContract $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }
    

}
