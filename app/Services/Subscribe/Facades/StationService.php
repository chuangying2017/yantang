<?php namespace App\Services\Subscribe\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Access
 * @package App\Services\Access\Facades
 */
class StationService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'StationService';
    }
}