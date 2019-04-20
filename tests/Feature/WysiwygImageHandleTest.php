<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WysiwygImageHandleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function unauthenticated_user_cannot_upload_image()
    {
        $this->json('POST', 'api/image-upload', [])->assertStatus(401);
    }

    /** @test */
    public function it_requires_an_valid_image()
    {
        $this->actingAs($this->user)
             ->json('POST', 'api/image-upload', ['image' => null])
             ->assertJsonStructure(['errors' => ['image']])
             ->assertStatus(422);

        $this->actingAs($this->user)
             ->json('POST', 'api/image-upload', ['image' => 'not-valid-image'])
             ->assertJsonStructure(['errors' => ['image']])
             ->assertStatus(422);
    }

    /** @test */
    public function authenticated_user_can_upload_image()
    {
        $file = UploadedFile::fake()->image('image.jpg');

        $this->actingAs($this->user)
             ->json('POST', 'api/image-upload', ['image' => $file]);

        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }

    /** @test */
    public function it_requires_an_array_of_names_when_deleting_images()
    {
        $this->actingAs($this->user)
             ->json('DELETE', 'api/image-delete')
             ->assertStatus(422);

        $this->actingAs($this->user)
             ->json('DELETE', 'api/image-delete', ['name' => null])
             ->assertStatus(422);

        $this->actingAs($this->user)
             ->json('DELETE', 'api/image-delete', ['name' => 'not-array'])
             ->assertStatus(422);
    }

    /** @test */
    public function authenticated_user_can_delete_image()
    {
        $fileOne = UploadedFile::fake()->image('image-one.jpg');
        $fileTwo = UploadedFile::fake()->image('image-two.jpg');

        $this->actingAs($this->user)
             ->json('DELETE', 'api/image-delete', ['name' => [$fileOne, $fileTwo]]);

        Storage::disk('public')->assertMissing('images/' . $fileOne->hashName());
        Storage::disk('public')->assertMissing('images/' . $fileTwo->hashName());
    }
}
