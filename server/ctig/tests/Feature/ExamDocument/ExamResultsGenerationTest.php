<?php

namespace Tests\Feature\ExamDocument;

use App\Enums\UserRoles;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class ExamResultsGenerationTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->examiner()->create();

        Carbon::setTestNow(now());
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inPast()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->getJson(route('exam.documents.results', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_access_roles(){
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create();

        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Director, UserRoles::Examiner],
            method:'GET',
            route: route('exam.documents.results', ['exam' => $exam])
        );
    }
}
