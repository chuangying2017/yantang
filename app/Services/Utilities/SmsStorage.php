<?php namespace App\Services\Utilities;

use Cache;

class SmsStorage {

    public function set($key, $value)
    {
        Cache::put($key, $value, 30);
    }

    public function get($key, $default)
    {
        return Cache::get($key, $default);
    }

    public function forget($key)
    {
        Cache::forget($key);
    }

}
