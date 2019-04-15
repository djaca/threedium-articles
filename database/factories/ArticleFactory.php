<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title'     => $faker->words(2, true),
        'body'      => $faker->realText(),
        'author_id' => function () {
            factory(User::class)->create()->id;
        }
    ];
});
