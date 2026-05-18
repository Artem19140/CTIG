<?php

namespace Tests\Feature\Enrollment;

use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentStatementGenerationTest extends TestCase
{
    use RefreshDatabase;
    protected Employee $actor;
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->actor = Employee::factory()
            ->operator()
            ->create([
                'center_id' => $this->center->id
        ]);
        Carbon::setTestNow(now());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    
    public function test_success_enrollment_statement_generating(): void{

        $enrollment = Enrollment::factory()->create([
            'center_id' => $this->center->id
        ]);

        $response = $this->actingAs($this->actor)
            ->getJson(route('enrollments.statements', ['enrollment' => $enrollment]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertStatus(200);
    }

    public function test_fail_enrollment_statement_generating_another_center(): void{

        $enrollment = Enrollment::factory()->create();

        $response = $this->actingAs($this->actor)
            ->getJson(route('enrollments.statements', ['enrollment' => $enrollment]));

        $response->assertForbidden();
    }
}
