<?php namespace App\Services\Product\Brand;

use App\Models\Channel;

class ChannelService {

    public static function create($data)
    {
        $channel = Channel::updateOrCreate(
            ['name' => array_get($data, 'name')],
            [
                'name'        => array_get($data, 'name'),
                'index'       => array_get($data, 'index', 1),
                'active'      => array_get($data, 'active', 1),
                'cover_image' => array_get($data, 'cover_image', null),
            ]);

        $brand_ids = array_get($data, 'brand_ids', null);

        if ( ! is_null($brand_ids)) {

            $brand_ids = to_array($brand_ids);
            $channel->brands()->sync($brand_ids);
        }

        return $channel;
    }

    public static function update($id, $data)
    {
        $channel = Channel::findOrFail($id);
//        $channel->fill($data);
        $channel->name = array_get($data, 'name', $channel->name);
        $channel->index = array_get($data, 'index', $channel->index);
        $channel->active = array_get($data, 'active', $channel->active);
        $channel->cover_image = array_get($data, 'cover_image', $channel->cover_image);

        $brand_ids = array_get($data, 'brand_ids', null);

        if ( ! is_null($brand_ids)) {

            $brand_ids = to_array($brand_ids);
            $channel->brands()->sync($brand_ids);
        }

        $channel->save();

        return $channel;
    }

    public static function delete($id)
    {
        $channel = Channel::findOrFail($id);
        $channel->brands()->detach();

        return $channel->delete();
    }

    public static function show($id)
    {
        if (is_numeric($id)) {
            $channel = Channel::with('brands')->find($id);
        } else {
            $channel = Channel::with('brands')->where('name', $id)->first();
        }

        return $channel;
    }

    public static function lists($all = 0)
    {
        $query = Channel::with('brands')->orderBy('index');

        if ( ! $all) {
            $query->where('active', 1);
        }

        $channels = $query->get();

        return $channels;
    }


    public static function getBrandsId($channel_id)
    {
        return Channel::find($channel_id)->brands()->lists('brand_id')->toArray();
    }

}
