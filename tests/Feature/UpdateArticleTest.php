<?php

namespace Tests\Feature;

use App\Article;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->article = factory(Article::class)->create([
            'author_id' => $this->user->id
        ]);
    }

    /** @test */
    public function unauthorized_user_may_not_update_article()
    {
        $this->json('PATCH', route('articles.update', $this->article->id), [])
             ->assertStatus(401);

        $this->actingAs(factory(User::class)->create())
             ->json('PATCH', route('articles.update', $this->article->id), [])
             ->assertStatus(403);
    }

    /** @test */
    public function it_requires_a_title()
    {
        $this->updateArticle(['title' => null])
             ->assertJsonStructure(['errors' => ['title']])
             ->assertStatus(422);
    }

    /** @test */
    public function it_requires_a_body()
    {
        $this->updateArticle(['body' => null])
             ->assertJsonStructure(['errors' => ['body']])
             ->assertStatus(422);
    }

    /** @test */
    public function article_can_be_updated_by_its_author()
    {
        $data = ['title' => 'Updated Article', 'body' => 'updated body'];

        $this->updateArticle($data)
             ->assertJson([
                'status'  => 'success',
                'message' => 'Article updated successfully'
            ]);

        $this->assertDatabaseHas('articles', [
            'title'     => $data['title'],
            'body'      => $data['body']
        ]);
    }

    private function updateArticle($overrides = [])
    {
        return $this->actingAs($this->user)
                    ->json('PATCH', route('articles.update', $this->article->id), $overrides);
    }
}
