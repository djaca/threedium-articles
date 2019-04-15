<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function unauthenticated_user_may_not_create_article()
    {
        $this->json('POST', route('articles.store'))->assertStatus(401);
    }

    /** @test */
    public function it_requires_a_title()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['title' => null])
             ->assertJsonStructure(['errors' => ['title']])
             ->assertStatus(422);
    }

    /** @test */
    public function it_requires_a_body()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['body' => null])
             ->assertJsonStructure(['errors' => ['body']])
             ->assertStatus(422);
    }

    /** @test */
    public function authenticated_can_create_article()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), [
                 'title' => 'New article',
                 'body'  => 'Article body'
             ])
             ->assertJson([
                 'status' => 'success',
                 'message' => 'Article created successfully'
             ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'New article',
            'body'  => 'Article body',
            'author_id' => $this->user->id
        ]);
    }
}
