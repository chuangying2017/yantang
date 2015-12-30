<?php
/*
 * Test routes
 */

use App\Services\Product\ProductConst;

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {

        $product_metas = \App\Models\ProductMeta::get();
        foreach ($product_metas as $product_key => $product_meta) {
            $attributes = json_decode($product_meta->attributes, true);
            foreach ($attributes as $attribute_key => $attribute) {
                $attribute_data = \App\Models\AttributeValue::with('attribute')->find($attribute['values'][0]['id']);
                $attributes[ $attribute_key ]['name'] = $attribute_data['attribute']['name'];
                $attributes[ $attribute_key ]['id'] = $attribute_data['attribute']['id'];
            }
            $product_meta->attributes = json_encode($attributes);
            $product_meta->save();
        }

        return $product_metas;
    });

    Route::get('test/token', function () {
        return csrf_token();
    });

    Route::get('/test/login/{id}', function ($id) {
        Auth::user()->logout();
        Auth::user()->loginUsingId($id);

        return $id . ' login ' . (Auth::user()->check() ? ' success' : ' fail');
    });

    Route::get('/test/logout', function () {
        Auth::user()->logout();
    });

}
