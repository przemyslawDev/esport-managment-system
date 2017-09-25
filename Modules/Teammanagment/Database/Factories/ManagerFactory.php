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

$factory->define(Modules\Teammanagment\Models\Manager::class, function (Faker $faker) {

    return [
        'nickname' => $faker->userName,
        'employee_id' => function () {
            return factory(Modules\Administration\Models\Employee::class)->create()->id;
        }
    ];
});
