<?php

namespace Tests\Feature;

use App\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadArticlesTest extends TestCase
{
    use RefreshDatabase;

    protected $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->article = factory(Article::class)->create();
    }

    /** @test */
    public function anyone_can_view_all_articles()
    {
        $this->json('GET', route('articles.all'))
             ->assertSee($this->article->title)
             ->assertSee($this->article->author->name);
    }

    /** @test */
    public function anyone_can_read_a_single_article()
    {
        $this->get(route('articles.show', $this->article->id))
             ->assertSee($this->article->title);
    }
}
