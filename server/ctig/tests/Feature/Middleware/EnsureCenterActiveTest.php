<?php

namespace Tests\Feature\Middleware;

use App\Models\Center;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnsureCenterActiveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_success(): void
    {
        $center = Center::factory()->notActive()->create();
        
        $user = User::factory()->hasChangePassword()->create([
            'center_id' => $center->id
        ]);

        $response = $this->actingAs($user)
            ->get('/exams');

        $response->assertRedirectToRoute('login');
    }
}
