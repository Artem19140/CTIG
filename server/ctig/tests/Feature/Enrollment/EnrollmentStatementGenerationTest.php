<?php

namespace Tests\Feature\Enrollment;

use App\Enums\UserRoles;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class EnrollmentStatementGenerationTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->operator()->create(['center_id' => $this->center->id]);
        Carbon::setTestNow(now());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    
    public function test_success(): void
    {
        $user = User::factory()->operator()->create(['center_id' => $this->center->id]);

        $enrollment = Enrollment::factory()->create(['center_id' => $this->center->id]);

        $response = $this->actingAs($user)
            ->getJson(route('enrollments.statements', ['enrollment' => $enrollment]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertStatus(200);
    }

    public function test_access_roles(){
        $exam = Exam::factory()->inFuture()->create(['center_id' => $this->center->id]);

        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);

        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Operator],
            method:'GET',
            route: route('enrollments.statements', ['enrollment' => $enrollment]),
            center:$this->center
        );
    }
}
