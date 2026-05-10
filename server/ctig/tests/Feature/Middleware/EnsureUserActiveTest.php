<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureUserActiveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_success(): void
    {
        $user = User::factory()->notActive()->create();

        $response = $this->actingAs($user)
            ->get('/exams');
        $this->assertGuest('web');
        $response->assertRedirectToRoute('login');
    }
}
