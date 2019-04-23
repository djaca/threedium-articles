<?php

namespace Tests\Unit;

use App\Article;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ArticleTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_has_all_fields()
    {
        $article = factory(Article::class)->create([
            'title'     => 'New article',
            'body'      => 'Body of the article',
            'subtitle'  => 'Excerpt of the article',
            'author_id' => factory(User::class)->create()->id,
            'image'     => 'image.jpg'
        ]);

        $this->assertEquals('New article', $article->title);
        $this->assertEquals('Body of the article', $article->body);
        $this->assertEquals('Excerpt of the article', $article->subtitle);
        $this->assertInstanceOf(User::class, $article->author);
        $this->assertEquals(asset('storage/images/image.jpg'), $article->image);
    }
}
