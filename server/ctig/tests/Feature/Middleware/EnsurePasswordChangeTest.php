<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsurePasswordChangeTest extends TestCase
{
    use RefreshDatabase;
    public function test_success(): void
    {
        $user = User::factory()->hasChangePassword()->create();

        $response = $this->actingAs($user)
            ->get('/exams');

        $response->assertRedirectToRoute('password.change');
    }
}
