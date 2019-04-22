<?php

namespace Tests\Feature;

use App\Article;
use App\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthorized_user_may_not_delete_article()
    {
        $article = factory(Article::class)->create();

        $this->json('DELETE', route('articles.destroy', $article->id))
             ->assertStatus(401);

        $this->actingAs(factory(User::class)->create())
             ->json('DELETE', route('articles.destroy', $article->id))
             ->assertStatus(403);
    }

    /** @test */
    public function article_can_be_deleted_by_its_author()
    {
        $user = factory(User::class)->create();

        $article = factory(Article::class)->create(['author_id' => $user->id]);

        $this->actingAs($user)
             ->json('DELETE', route('articles.destroy', $article->id))
             ->assertJson([
                 'status'  => 'success',
                 'message' => 'Article deleted successfully'
             ]);

        $this->assertDatabaseMissing('articles', [
            'title'     => $article->title,
            'body'      => $article->body,
            'excerpt'   => $article->excerpt,
            'author_id' => $user->id,
            'image'     => $article->getOriginal('image')
        ]);

        Storage::assertMissing($article->getOriginal('image'));
    }
}
