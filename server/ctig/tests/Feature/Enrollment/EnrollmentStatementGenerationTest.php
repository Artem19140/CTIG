<?php

namespace Tests\Feature\Enrollment;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnrollmentStatementGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        $user = User::factory()->create();
        $enrollment = Enrollment::factory()->create();
        $response = $this->actingAs($user)
            ->getJson(route('enrollments.statements', ['enrollment' => $enrollment]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertStatus(200);
    }
}
