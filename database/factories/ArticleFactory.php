<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title'     => $faker->words(2, true),
        'body'      => $faker->realText(2000),
        'excerpt'   => $faker->sentence,
        'author_id' => function () {
            return factory(User::class)->create()->id;
        },
        'image' => function () use ($faker) {
            $img = $faker->image(storage_path('app/public/images'), 918, 400, null, false);

            return $img;
        }
    ];
});
