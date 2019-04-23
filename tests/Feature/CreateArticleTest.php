<?php

namespace Tests\Feature;

use App\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
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

        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['title' => $this->article->title])
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
    public function it_requires_a_subtitle()
    {
        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), ['subtitle' => null])
             ->assertJsonStructure(['errors' => ['subtitle']])
             ->assertStatus(422);
    }

    /** @test */
    public function authenticated_can_create_article()
    {
        $data = [
            'title'    => 'New article',
            'body'     => 'Article body',
            'subtitle' => 'Article subtitle',
            'image'    => $file = UploadedFile::fake()->image('image.jpg')
        ];

        $this->actingAs($this->user)
             ->json('POST', route('articles.store'), $data)
             ->assertJson([
                 'status'  => 'success',
                 'message' => 'Article created successfully',
                 'article' => [
                     'title' => $data['title']
                 ]
             ]);

        $this->assertDatabaseHas('articles', [
            'title'     => $data['title'],
            'body'      => $data['body'],
            'subtitle'  => $data['subtitle'],
            'author_id' => $this->user->id,
            'image'     => $file->hashName()
        ]);

        Storage::assertExists($file->hashName());
    }
}
