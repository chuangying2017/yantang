<?php 


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