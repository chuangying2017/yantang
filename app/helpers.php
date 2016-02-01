<?php

/**
 * Global helpers file with misc functions
 **/

if ( ! function_exists('app_name')) {
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

if ( ! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function
     */
    function access()
    {
        return app('access');
    }
}

if ( ! function_exists('javascript')) {
    /**
     * Access the javascript helper
     */
    function javascript()
    {
        return app('JavaScript');
    }
}

if ( ! function_exists('gravatar')) {
    /**
     * Access the gravatar helper
     */
    function gravatar()
    {
        return app('gravatar');
    }
}
if ( ! function_exists('api_route')) {

    function api_route($name, $version = 'v1')
    {
        return app('Dingo\Api\Routing\UrlGenerator')->version($version)->route($name);
    }
}

if ( ! function_exists('image_url')) {

    function image_url($name)
    {
        return env('QINIU_DEFAULT_DOMAIN') . '/' . $name;
    }
}

if ( ! function_exists('current_url_paras')) {

    function current_url_paras(Array $except = [])
    {
        $query = '';
        $paras = Request::all();
        foreach ($paras as $key => $para) {
            if ( ! in_array($key, $except)) {
                $query .= '&' . $key . '=' . $para;
            }
        }

        return $query;
    }
}

if ( ! function_exists('qiniu_asset')) {

    function qiniu_asset($path)
    {
        return env('QINIU_PREFIX_URL') . $path;
    }
}


if ( ! function_exists('array_to_string')) {

    function array_to_string($value, $glue = ',')
    {
        return is_array($value) ? implode($glue, $value) : $value;
    }
}

if ( ! function_exists('to_array')) {

    function to_array($value)
    {
        return is_array($value) ? $value : [$value];
    }
}

if ( ! function_exists('display_price')) {

    function display_price($price)
    {
        return bcdiv($price, 100, 2);
    }
}

if ( ! function_exists('display_discount')) {

    function display_discount($price)
    {
        return bcdiv($price, 100, 2) . '折';
    }
}

if ( ! function_exists('display_percentage')) {

    function display_percentage($percentage)
    {
        return bcdiv($percentage, 100, 2);
    }
}

if ( ! function_exists('store_percentage')) {

    function store_percentage($percentage)
    {
        return bcmul($percentage, 100, 0);
    }
}

if ( ! function_exists('store_price')) {

    function store_price($price)
    {
        return bcmul($price, 100, 0);
    }
}

if ( ! function_exists('get_current_auth_user')) {

    function get_current_auth_user()
    {

        if ( ! \Tymon\JWTAuth\Facades\JWTAuth::getToken()) {
            if (Auth::check()) {
                return Auth::user();
            }

            return false;
        }

        try {
            $user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();

            return $user;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}








