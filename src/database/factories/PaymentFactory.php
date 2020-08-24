<?php

/** @var Factory $factory */

use App\Models\Payment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Payment::class, static function (Faker $faker) {
    return [
        'target' => $faker->text(),
        'callback_url' => $faker->url.'/callback.php',
        'paid' => random_int(0, 1),
        'callback_at' => now(),
        'expires_at' => now()->addMinutes(30),
        'amount' => $faker->randomFloat(4, 10, 10 ^ 9),
    ];
});
