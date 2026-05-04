<?php

namespace Tests\Feature\Enrollment;

use App\Models\Attempt;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentCancellTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected string $model;
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

    protected function deleteEnrollment(int $enrollmentId){
        return $this->actingAs($this->user)
            ->deleteJson("/enrollments/$enrollmentId");
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $exam = Exam::factory()
            ->inFuture()
            ->create();

        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id
        ]);

        $response = $this->deleteEnrollment($enrollment->id);
        $response->assertOk();
    }

    public function test_fail_has_attempt(): void
    {

        $exam = Exam::factory()
            ->now()
            ->create();

        $enrollment = Enrollment::factory()
            ->has(Attempt::factory())
            ->create([
                'exam_id' => $exam->id
            ]);

        $response = $this->deleteEnrollment($enrollment->id);
        $response->assertBadRequest();
    }

    public function test_fail_exam_past(): void
    {

        $exam = Exam::factory()
            ->inPast()
            ->create();

        $enrollment = Enrollment::factory()
            ->create([
                'exam_id' => $exam->id
            ]);

        $response = $this->deleteEnrollment($enrollment->id);
        $response->assertBadRequest();
    }

    public function test_fail_exam_going(): void
    {

        $exam = Exam::factory()
            ->now()
            ->create();

        $enrollment = Enrollment::factory()
            ->create([
                'exam_id' => $exam->id
            ]);

        $response = $this->deleteEnrollment($enrollment->id);
        $response->assertBadRequest();
    }
}
