<?php

use App\Article;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create(['email' => 'admin@example.com']);

        factory(Article::class, 11)->create(['author_id' => $user->id]);
        factory(Article::class, 1)->create();
    }
}
