<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_all_fields()
    {
        $user = factory(User::class)->create([
            'name' => 'John Doe',
            'email' => 'johnDoe@gmail.com',
            'password' => bcrypt('secret')
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johnDoe@gmail.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
    }
}
