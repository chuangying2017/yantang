<?php namespace App\Services\Home;

use App\Models\Banner;

class BannerService {

    const TYPE_OF_SLIDER = 'slider';

    const TYPE_OF_GRID = 'grid';

    public static function create($input)
    {
        $input['type'] = array_get($input, 'type', self::TYPE_OF_SLIDER);

        $banner_data = array_only($input, ['title', 'url', 'cover_image', 'index', 'type']);

        $banner = Banner::create($banner_data);

        return $banner;
    }

    public static function update($id, $input)
    {
        $banner = Banner::findOrFail($id);

        $banner->fill(array_only($input, ['title', 'url', 'cover_image', 'index']));
        $banner->type = $input['type'] = array_get($input, 'type', $banner->type);
        $banner->save();

        return $banner;
    }

    public static function show($id)
    {
        return Banner::findOrFail($id);
    }

    public static function delete($id)
    {
        return Banner::destroy($id);
    }

    public static function listByType($type = null, $group = false)
    {
        if (is_null($type)) {
            $sliders = Banner::where('type', self::TYPE_OF_SLIDER)->orderBy('index')->get(['id', 'title', 'url', 'cover_image', 'index', 'type']);
            $grids = Banner::where('type', self::TYPE_OF_GRID)->orderBy('index')->get(['id', 'title', 'url', 'cover_image', 'index', 'type']);

            $data = [
                'sliders' => $sliders,
                'grids'   => $grids,
            ];
        } else {
            $data = Banner::where('type', $type)->orderBy('index')->get(['id', 'title', 'url', 'cover_image', 'index', 'type']);
        }

        return $data;
    }
}
