<?php namespace App\Services\Home;

use App\Models\Nav;

class NavService {

    public static function nav()
    {
        $navs = Nav::orderBy('index')->get(['id', 'name', 'type', 'url', 'index']);

        return $navs;
    }

    public static function show($id)
    {
        return Nav::findOrFail($id);
    }


    public static function create($data)
    {

        $nav_data = array_only($data, ['name', 'type', 'url', 'index']);

        $nav = Nav::create($nav_data);

        return $nav;
    }

    public static function update($id, $data)
    {
        $nav_data = array_only($data, ['name', 'type', 'url', 'index']);
        $nav = Nav::findOrFail($id);
        $nav->fill($nav_data);
        $nav->save();

        return $nav;
    }

    public static function delete($id)
    {
        $id = to_array($id);

        Nav::whereIn('id', $id)->delete();
    }


}
