<?php

namespace Tests\Feature\Enrollment;

use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentStatementGenerationTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->operator()->create();
        Carbon::setTestNow(now());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    
    public function test_example(): void
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->operator()->create();
        $enrollment = Enrollment::factory()->create();
        $response = $this->actingAs($user)
            ->getJson(route('enrollments.statements', ['enrollment' => $enrollment]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertStatus(200);
    }
}
