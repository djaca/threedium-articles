<?php

namespace Tests\Unit;

use App\Article;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_has_all_fields()
    {
        $article = factory(Article::class)->create([
            'title'     => 'New article',
            'body'      => 'Body of the article',
            'author_id' => factory(User::class)->create()->id,
            'image'     => 'images/image.jpg'
        ]);

        $this->assertEquals('New article', $article->title);
        $this->assertEquals('Body of the article', $article->body);
        $this->assertEquals('Body of the article', $article->excerpt);
        $this->assertInstanceOf(User::class, $article->author);
        $this->assertEquals(asset('storage/images/image.jpg'), $article->image);
    }
}
