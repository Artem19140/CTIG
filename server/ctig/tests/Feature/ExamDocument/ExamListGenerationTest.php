<?php

namespace Tests\Feature\ExamDocument;

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

class ExamListGenerationTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->examiner()->create(['center_id' => $this->center->id]);

        Carbon::setTestNow(now());
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success_examiter_attached(): void
    {
        $this->withoutExceptionHandling();
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create(['center_id' => $this->center->id]);
        $exam->examiners()->attach($this->user);
        $response = $this
            ->actingAs($this->user)
            ->getJson(route('exam.documents.list', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_access_roles(){
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create(['center_id' => $this->center->id]);

        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Operator],
            method:'GET',
            route: route('exam.documents.list', ['exam' => $exam])
        );
    }
}
