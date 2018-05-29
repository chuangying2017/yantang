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

    function api_route($name, $version = 'v1', $parameters = [])
    {
        return app('Dingo\Api\Routing\UrlGenerator')->version($version)->route($name, $parameters);
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

if (!function_exists('is_zh_phone')) {
    function is_zh_phone($phone)
    {
        return is_numeric($phone) && strlen($phone) == 11;
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


/**
 * URL constants as defined in the PHP Manual under "Constants usable with
 * http_build_url()".
 *
 * @see http://us2.php.net/manual/en/http.constants.php#http.constants.url
 */
if (!defined('HTTP_URL_REPLACE')) {
    define('HTTP_URL_REPLACE', 1);
}
if (!defined('HTTP_URL_JOIN_PATH')) {
    define('HTTP_URL_JOIN_PATH', 2);
}
if (!defined('HTTP_URL_JOIN_QUERY')) {
    define('HTTP_URL_JOIN_QUERY', 4);
}
if (!defined('HTTP_URL_STRIP_USER')) {
    define('HTTP_URL_STRIP_USER', 8);
}
if (!defined('HTTP_URL_STRIP_PASS')) {
    define('HTTP_URL_STRIP_PASS', 16);
}
if (!defined('HTTP_URL_STRIP_AUTH')) {
    define('HTTP_URL_STRIP_AUTH', 32);
}
if (!defined('HTTP_URL_STRIP_PORT')) {
    define('HTTP_URL_STRIP_PORT', 64);
}
if (!defined('HTTP_URL_STRIP_PATH')) {
    define('HTTP_URL_STRIP_PATH', 128);
}
if (!defined('HTTP_URL_STRIP_QUERY')) {
    define('HTTP_URL_STRIP_QUERY', 256);
}
if (!defined('HTTP_URL_STRIP_FRAGMENT')) {
    define('HTTP_URL_STRIP_FRAGMENT', 512);
}
if (!defined('HTTP_URL_STRIP_ALL')) {
    define('HTTP_URL_STRIP_ALL', 1024);
}
if (!function_exists('http_build_url')) {
    /**
     * Build a URL.
     *
     * The parts of the second URL will be merged into the first according to
     * the flags argument.
     *
     * @param mixed $url     (part(s) of) an URL in form of a string or
     *                       associative array like parse_url() returns
     * @param mixed $parts   same as the first argument
     * @param int   $flags   a bitmask of binary or'ed HTTP_URL constants;
     *                       HTTP_URL_REPLACE is the default
     * @param array $new_url if set, it will be filled with the parts of the
     *                       composed url like parse_url() would return
     * @return string
     */
    function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = array())
    {
        is_array($url) || $url = parse_url($url);
        is_array($parts) || $parts = parse_url($parts);
        isset($url['query']) && is_string($url['query']) || $url['query'] = null;
        isset($parts['query']) && is_string($parts['query']) || $parts['query'] = null;
        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');
        // HTTP_URL_STRIP_ALL and HTTP_URL_STRIP_AUTH cover several other flags.
        if ($flags & HTTP_URL_STRIP_ALL) {
            $flags |= HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS
                | HTTP_URL_STRIP_PORT | HTTP_URL_STRIP_PATH
                | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT;
        } elseif ($flags & HTTP_URL_STRIP_AUTH) {
            $flags |= HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS;
        }
        // Schema and host are alwasy replaced
        foreach (array('scheme', 'host') as $part) {
            if (isset($parts[$part])) {
                $url[$part] = $parts[$part];
            }
        }
        if ($flags & HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key])) {
                    $url[$key] = $parts[$key];
                }
            }
        } else {
            if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
                if (isset($url['path']) && substr($parts['path'], 0, 1) !== '/') {
                    // Workaround for trailing slashes
                    $url['path'] .= 'a';
                    $url['path'] = rtrim(
                            str_replace(basename($url['path']), '', $url['path']),
                            '/'
                        ) . '/' . ltrim($parts['path'], '/');
                } else {
                    $url['path'] = $parts['path'];
                }
            }
            if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
                if (isset($url['query'])) {
                    parse_str($url['query'], $url_query);
                    parse_str($parts['query'], $parts_query);
                    $url['query'] = http_build_query(
                        array_replace_recursive(
                            $url_query,
                            $parts_query
                        )
                    );
                } else {
                    $url['query'] = $parts['query'];
                }
            }
        }
        if (isset($url['path']) && $url['path'] !== '' && substr($url['path'], 0, 1) !== '/') {
            $url['path'] = '/' . $url['path'];
        }
        foreach ($keys as $key) {
            $strip = 'HTTP_URL_STRIP_' . strtoupper($key);
            if ($flags & constant($strip)) {
                unset($url[$key]);
            }
        }
        $parsed_string = '';
        if (!empty($url['scheme'])) {
            $parsed_string .= $url['scheme'] . '://';
        }
        if (!empty($url['user'])) {
            $parsed_string .= $url['user'];
            if (isset($url['pass'])) {
                $parsed_string .= ':' . $url['pass'];
            }
            $parsed_string .= '@';
        }
        if (!empty($url['host'])) {
            $parsed_string .= $url['host'];
        }
        if (!empty($url['port'])) {
            $parsed_string .= ':' . $url['port'];
        }
        if (!empty($url['path'])) {
            $parsed_string .= $url['path'];
        }
        if (!empty($url['query'])) {
            $parsed_string .= '?' . $url['query'];
        }
        if (!empty($url['fragment'])) {
            $parsed_string .= '#' . $url['fragment'];
        }
        $new_url = $url;
        return $parsed_string;
    }
}

function submitStatus($status){
    if($status){
        $arr = ['status'=>1,'msg'=>'修改成功'];
    }else{
        $arr = ['status'=>2,'msg'=>'修改失败'];
    }
    return $arr;
}

if (!function_exists('transformStationOrder')) {

    function transformStationOrder($orders){

        $product_skus_info = [];
        foreach ($orders as $key => $order) {
            if (count($order['skus']) <= 0) {
                continue;
            }
            foreach ($order['skus'] as $sku) {

                $sku_key = $sku['product_sku_id'];
                if (isset($product_skus_info[$sku_key])) {
                    $product_skus_info[$sku_key]['quantity'] += $sku['quantity'];
                } else {
                    $product_skus_info[$sku_key]['staff_id'] = $order['staff_id'];
                    $product_skus_info[$sku_key]['product_id'] = $sku['product_id'];
                    $product_skus_info[$sku_key]['product_sku_id'] = $sku['product_sku_id'];
                    $product_skus_info[$sku_key]['quantity'] = $sku['quantity'];
                    $product_skus_info[$sku_key]['name'] = $sku['name'];
                }
            }
        }

        return $product_skus_info;
    }
}








