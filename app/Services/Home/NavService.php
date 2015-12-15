<?php namespace App\Services\Home;

use App\Models\Nav;

class NavService {

    public static function nav()
    {
        return Nav::get(['id', 'name', 'type', 'url']);
    }


}
