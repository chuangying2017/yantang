<?php
/*
 * Test routes
 */

use App\Services\Product\ProductConst;

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {

        $excel_path = storage_path('upload') . '/' . 'dflr.csv';

        Excel::filter('chunk')->load($excel_path)->chunk(10000, function ($results) {
            $results = $results->toArray();

//            info($results);

            $data = [];
            foreach ($results as $excel_record) {

                $province_no = trim($excel_record['province_no']);
                $city_no = trim($excel_record['province_no']) . trim($excel_record['city_no']);
                $province_name = trim($excel_record['province_name']);
                $city_name = trim($excel_record['city_name']);

                if (is_null(trim($excel_record['regoin_no']))) {
                    $regoin_no = $city_no;
                    $regoin_name = $city_name;
                } else {
                    $regoin_no = trim($excel_record['city_no']) . trim($excel_record['regoin_no']);
                    $regoin_name = trim($excel_record['regoin_name']);
                }

                if (is_null($province_no) || ! $province_no) {
                    continue;
                }

                array_set($data, $province_no . '.name', $province_name);
                array_set($data, $province_no . '.no', $province_no);
                array_set($data, $province_no . '.level', \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_PROVINCE);
                array_set($data, $province_no . '.children.' . $city_no . '.name', $city_name);
                array_set($data, $province_no . '.children.' . $city_no . '.no', $city_no);
                array_set($data, $province_no . '.children.' . $city_no . '.level', \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_CITY);

                array_set($data, $province_no . '.children.' . $city_no . '.children.' . $regoin_no . '.no', $regoin_no);
                array_set($data, $province_no . '.children.' . $city_no . '.children.' . $regoin_no . '.name', $regoin_name);
                array_set($data, $province_no . '.children.' . $city_no . '.children.' . $regoin_no . '.level', \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_REGION);

            }

            \App\Models\Agent::truncate();
            \App\Models\Agent::buildTree($data);

//            info($data);
        });

        return 1;

    });

    Route::get('test/agent', function () {
        $agent_order = \App\Models\AgentOrder::find(30);
        event(new \App\Services\Agent\Event\NewAgentOrder($agent_order));

        return 1;
    });

    Route::get('test/token', function () {
        return csrf_token();
    });

    Route::get('/test/login/{id}', function ($id) {
        Auth::logout();
        Auth::loginUsingId($id);

        return $id . ' login ' . (Auth::check() ? ' success' : ' fail');
    });

    Route::get('/test/logout', function () {
        Auth::user()->logout();
    });

}
