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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'email' => $faker->email,
        'level' => 'user',
        'ip'      => $faker->ipv4,
    ];
});

$factory->define(App\MainQueue::class, function (Faker\Generator $faker) {
    return [
        'queue_name' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'counter' => $faker->sentence($nbWords = 2, $variableNbWords = true),
        'start' => $faker->dateTimeBetween($startDate = 'now' , $endDate = '+1 day'),
        'end' => $faker->dateTimeBetween($startDate = '+1 day' , $endDate = '+2 day'),
        'status' => 'ready',
        'current_count' => $faker->numberBetween($min = 1, $max = 49),
        'max_count' => $faker->numberBetween($min = 1, $max = 50),
        'owner' => $faker->numberBetween($min = 1, $max = 19),
    ];
});

$factory->define(App\UserQueue::class, function (Faker\Generator $faker) {
    return [
        'queue_id' => $faker->numberBetween($min = 1, $max = 19),
        'user_id' => $faker->numberBetween($min = 1, $max = 19),
        'queue_captcha' => $faker->bothify('??##??##??'),
        'queue_time' => $faker->dateTimeBetween($startDate = 'now' , $endDate = '+1 day'),
        'ip'      => $faker->ipv4,
    ];
});

$factory->define(App\UserInformation::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 19),
        'name' => $faker->name,
        'gender' => 'male',
        'card_id' => $faker->numerify('############'),
        'address' => $faker->address,
        'tel' => $faker->PhoneNumber,
        'birthday' => $faker->dateTimeBetween($startDate = '-35 years' , $endDate = '-15 years'),
    ];
});