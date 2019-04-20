<?php

namespace Tests\Feature;

use App\Article;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        $this->get(route('articles.create'))->assertRedirect(route('login'));
    }

    /** @test */
    public function it_requires_a_title_and_must_be_unique()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['title' => null])
             ->assertJsonStructure(['errors' => ['title']])
             ->assertStatus(422);

        $article = factory(Article::class)->create();

        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['title' => $article->title])
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
    public function it_requires_an_valid_image()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['image' => null])
             ->assertJsonStructure(['errors' => ['image']])
             ->assertStatus(422);

        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['image' => 'not-valid-image'])
             ->assertJsonStructure(['errors' => ['image']])
             ->assertStatus(422);
    }

    /** @test */
    public function it_requires_an_excerpt()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['excerpt' => null])
             ->assertJsonStructure(['errors' => ['excerpt']])
             ->assertStatus(422);
    }

    /** @test */
    public function authenticated_can_create_article()
    {
        Storage::fake('public');

        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), [
                 'title'   => 'New article',
                 'body'    => 'Article body',
                 'excerpt' => 'Article excerpt',
                 'image'   => $file = UploadedFile::fake()->image('image.jpg')
             ])
             ->assertJson([
                 'status'  => 'success',
                 'message' => 'Article created successfully'
             ]);

        $this->assertDatabaseHas('articles', [
            'title'     => 'New article',
            'body'      => 'Article body',
            'excerpt'   => 'Article excerpt',
            'author_id' => $this->user->id,
            'image'     => 'images/' . $file->hashName()
        ]);

        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }
}
