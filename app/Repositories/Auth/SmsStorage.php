<?php namespace App\Repositories\Auth;

use Cache;

class SmsStorage {

    public function set($key, $value)
    {
        if (Cache::has($key)) {
            Cache::forget($key);
        }

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
