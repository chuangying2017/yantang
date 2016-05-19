<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'user_id' => str_random(10),
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Models\Product\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->colorName,
    ];
});

$factory->define(\App\Models\Product\Product::class, function (Faker\Generator $faker) {
    return [
        'product_id' => $faker->uuid,
        'merchant_id' => $faker->randomDigitNotNull,
        'category_id' => mt_rand(1, 15),
        'type' => 'entity',
        'price' => mt_rand(1000, 20000),
        'limit' => 100,
        'express_fee' => 1000,
        'title' => $faker->text(20),
        'digest' => $faker->text(100),
        'status' => 'pending',
    ];
});

$factory->define(\App\Models\Client\Account\Wallet::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'amount' => 0,
        'used_amount' => 0
    ];
});
