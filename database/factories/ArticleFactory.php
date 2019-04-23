<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title'     => $faker->words(2, true),
        'body'      => $faker->realText(2000),
        'excerpt'   => $faker->text(100),
        'author_id' => function () {
            return factory(User::class)->create()->id;
        },
        'image' => function () use ($faker) {
            if (app()->environment() === 'testing') {
                return 'image.jpg';
            }

            $filepath = storage_path('app/public/images');

            if(!File::exists($filepath)){
                File::makeDirectory($filepath);
            }

            /*
             * Default $faker->image() use https://lorempixel.com,
             * sometimes is down or not getting image...
             *
             * https://github.com/fzaninotto/Faker/issues/957
             */

            $file = Image::make('https://loremflickr.com/918/400')->encode('jpg');

            Storage::put($name = Str::random(40) . '.jpg', $file);

            return $name;
        }
    ];
});
