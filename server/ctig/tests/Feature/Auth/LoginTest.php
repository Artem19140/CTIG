<?php

namespace Tests\Feature\Auth;

use App\Models\Center;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_success(): void
    {
        $user = User::factory()->create([
            'password' => '1234567890'
        ]);
        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);
        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticated('web');
        $response->assertRedirect('/exams');
    }

    public function test_fail(): void
    {
        $user = User::factory()->create([
            'password' => '1234567890'
        ]);
        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '123456789'
        ]);
        $response->assertRedirectBack();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_fail_not_active_user(): void
    {
        $user = User::factory()
            ->notActive()
            ->create([
                'password' => '1234567890'
            ]);

        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);

       $response->assertSessionHasErrors(['email']);
    }

    public function test_fail_not_active_center(): void
    {
        $center = Center::factory()->notActive()->create();
        $user = User::factory()
            ->create([
                'password' => '1234567890',
                'center_id' => $center->id
            ]);

        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);

       $response->assertSessionHasErrors(['email']);
    }
}
