<?php

/**
 * Global helpers file with misc functions
 **/

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function
     */
    function access()
    {
        return app('access');
    }
}

if (!function_exists('javascript')) {
    /**
     * Access the javascript helper
     */
    function javascript()
    {
        return app('JavaScript');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper
     */
    function gravatar()
    {
        return app('gravatar');
    }
}
if (!function_exists('api_route')) {

    function api_route($name, $version = 'v1')
    {
        return app('Dingo\Api\Routing\UrlGenerator')->version($version)->route($name);
    }
}

if (!function_exists('image_url')) {

    function image_url($name)
    {
        return env('QINIU_DEFAULT_DOMAIN') . '/' . $name;
    }
}

if (!function_exists('current_url_paras')) {

    function current_url_paras(Array $except = [])
    {
        $query = '';
        $paras = Request::all();
        foreach ($paras as $key => $para) {
            if (!in_array($key, $except)) {
                $query .= '&' . $key . '=' . $para;
            }
        }

        return $query;
    }
}

if (!function_exists('qiniu_asset')) {

    function qiniu_asset($path)
    {
        return env('QINIU_PREFIX_URL') . $path;
    }
}


if (!function_exists('array_to_string')) {

    function array_to_string($value, $glue = ',')
    {
        return is_array($value) ? implode($glue, $value) : $value;
    }
}

if (!function_exists('to_array')) {

    function to_array($value)
    {
        return is_array($value) ? $value : [$value];
    }
}


if (!function_exists('merge_array')) {

    function merge_array()
    {
        $items = [];
        $input_items = func_get_args();

        foreach ($input_items as $input_item) {
            if (!is_null($input_item)) {
                $items = array_merge($items, to_array($input_item));
            }
        }

        return $items;
    }
}


if (!function_exists('display_price')) {

    function display_price($price)
    {
        return bcdiv($price, 100, 2);
    }
}

if (!function_exists('display_discount')) {

    function display_discount($price)
    {
        return bcdiv($price, 100, 2) . '折';
    }
}

if (!function_exists('display_percentage')) {

    function display_percentage($percentage)
    {
        return bcdiv($percentage, 100, 2);
    }
}

if (!function_exists('store_percentage')) {

    function store_percentage($percentage)
    {
        return bcmul($percentage, 100, 0);
    }
}

if (!function_exists('store_price')) {

    function store_price($price)
    {
        return bcmul($price, 100, 0);
    }
}

if (!function_exists('store_coordinate')) {

    function store_coordinate($coordinate)
    {
        return bcmul($coordinate, 1000000, 0);
    }
}

if (!function_exists('display_coordinate')) {

    function display_coordinate($coordinate)
    {
        return bcdiv($coordinate, 1000000, 6);
    }
}

if (!function_exists('generate_no')) {
    function generate_no($type = null)
    {
        return mt_rand(1, 9) . substr(date('Y'), -2) . date('md') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
    }
}


if (!function_exists('generate_bind_token')) {
    function generate_bind_token($id)
    {
        return sha1('token_' . $id);
    }
}

if (!function_exists('check_bind_token')) {
    function check_bind_token($id, $token)
    {
        return sha1('token_' . $id) == $token;
    }
}


if (!function_exists('array_multi_sort')) {
    function array_multi_sort($data, $criteria)
    {
        /**
         *  $criteria = array(
         *      'gold'=>'desc',
         *      'ts'=>'desc' //这里还可以根据需要继续加条件 如:'x'=>'asc'等
         *  );
         */
        usort($data, function ($a, $b) use ($criteria) {
            foreach ($criteria as $what => $order) {
                if (array_get($a, $what) == array_get($b, $what)) {
                    continue;
                }
                return (($order == 'desc') ? -1 : 1) * ((array_get($a, $what) < array_get($b, $what)) ? -1 : 1);
            }
            return 0;
        });

        return $data;
    }
}










