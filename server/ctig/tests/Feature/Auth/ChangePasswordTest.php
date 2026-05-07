<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    public function test_success(): void
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
