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
        'user_id'       => str_random(10),
        'login_account' => $faker->userName,
        'name'          => $faker->name,
        'user_name'     => $faker->userName,
        'mobile'        => $faker->phoneNumber,
        'email'         => $faker->email
    ];
});

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->colorName,
    ];
});
