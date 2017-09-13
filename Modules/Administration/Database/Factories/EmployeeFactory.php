<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Modules\Administration\Models\Employee::class, function (Faker $faker) {

    return [
        'user_id' => null,
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'office' => $faker->title,
        'birthdate' => $faker->date,
        'status' => 1
    ];
});
