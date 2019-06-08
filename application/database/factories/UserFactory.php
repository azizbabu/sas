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

// $factory->define(App\User::class, function (Faker $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->unique()->safeEmail,
//         'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
//         'remember_token' => str_random(10),
//     ];
// });

$factory->define(App\Student::class, function (Faker $faker) {
    
	$gender = array_rand(config('constants.gender'));

    return [
        'name' => $faker->name($gender),
        'father_name'	=> $faker->name('male'),
        'mother_name'	=> $faker->name('female'),
        'class'	=> 1,
        'section'	=> 'a',
        'roll'	=> 1,
        'gender' => $gender,
        'admission_date' => \Carbon::now()->startOfYear()->subYear(1), // secret
        'created_by' => 1,
    ];
});
