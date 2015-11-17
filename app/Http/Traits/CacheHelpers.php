<?php namespace App\Http\Traits;


trait CacheHelpers
{

    /**
     * 设置缓存，按需重载
     * @param string $cachename
     * @param mixed $value
     * @param int $expired | seconds
     * @return boolean
     */
    public function setCache($cachename, $value, $expired){
        $expiresAt = floor($expired / 60);
        Cache::put($cachename, $value, $expiresAt);
    }

    /**
     * 获取缓存，按需重载
     * @param string $cachename
     * @return mixed
     */
    public function getCache($cachename){
        return Cache::get($cachename);
    }

    /**
     * 清除缓存，按需重载
     * @param string $cachename
     * @return boolean
     */
    public function removeCache($cachename){
        return Cache::forget($cachename);
    }

}
