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

class ExamCodesGenerationTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->examiner()->create(['center_id' =>  $this->center->id]);

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
            ->inFuture()
            ->create(['center_id' =>  $this->center->id]);

        $exam->examiners()->attach($this->user);

        $response = $this
            ->actingAs($this->user)
            ->getJson(route('exam.documents.codes', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_fail_too_early_generation(): void
    {
        $exam = Exam::factory()->create([
            'begin_time' => Carbon::now()->addDay(),
            'center_id' =>  $this->center->id
        ]);

        $exam->examiners()->attach($this->user);

        $response = $this
            ->actingAs($this->user)
            ->getJson(route('exam.documents.codes.available', ['exam' => $exam]));

        $response->assertBadRequest();
    }

    public function test_fail_examiner_no_attach(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory())
            ->inFuture()
            ->create(['center_id' =>  $this->center->id]);

        $response = $this
            ->actingAs($this->user)
            ->getJson(route('exam.documents.codes', ['exam' => $exam]));
        $response->assertForbidden();
    }

    public function test_access_roles(){
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create();

        $this->accessRolesCheck(
            allowedRoles:[],
            method:'GET',
            route: route('exam.documents.codes', ['exam' => $exam])
        );
    }
}
