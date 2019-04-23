<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    /** @test */
    public function unauthorized_user_may_not_delete_article()
    {
        $this->json('DELETE', route('articles.destroy', $this->article->id))
             ->assertStatus(401);

        $this->actingAs(factory(User::class)->create())
             ->json('DELETE', route('articles.destroy', $this->article->id))
             ->assertStatus(403);
    }

    /** @test */
    public function article_can_be_deleted_by_its_author()
    {
        $this->actingAs($this->user)
             ->json('DELETE', route('articles.destroy', $this->article->id))
             ->assertJson([
                 'status'  => 'success',
                 'message' => 'Article deleted successfully'
             ]);

        $this->assertDatabaseMissing('articles', [
            'title'     => $this->article->title,
            'body'      => $this->article->body,
            'subtitle'  => $this->article->subtitle,
            'author_id' => $this->user->id,
            'image'     => $this->article->getOriginal('image')
        ]);

        Storage::assertMissing($this->article->getOriginal('image'));
    }
}
