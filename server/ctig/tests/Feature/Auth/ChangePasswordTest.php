<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        $user = User::factory()->create([
            'password' => '1234567890',
            'has_to_change_password' => true
        ]);
        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);

        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticated('web');
        $response->assertRedirect(route('password.change'));
    }
}
