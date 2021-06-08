<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
    $title = $faker->sentence(4);
    return [
        'title' => $title,
        'slug' => Str::slug($title),
        'content' => $faker->realText(),
        'user_id' => factory(User::class)->create()->id,
        'channel_id' => factory(Channel::class)->create()->id
    ];
});
