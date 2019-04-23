<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WysiwygImageHandleTest extends TestCase
{
    protected $image;

    protected function setUp(): void
    {
        parent::setUp();

        $this->image = UploadedFile::fake()->image('image.jpg');
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
        $this->actingAs($this->user)
             ->json('POST', 'api/image-upload', ['image' => $this->image]);

        Storage::assertExists($this->image->hashName());
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
        $this->authenticated_user_can_upload_image();

        $this->actingAs($this->user)
             ->json('DELETE', 'api/image-delete', ['name' => [$this->image->hashName()]]);

        Storage::assertMissing($this->image->hashName());
    }
}
