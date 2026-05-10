<?php

namespace Tests\Feature\Enrollment;

use App\Enums\UserRoles;
use App\Models\Attempt;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class EnrollmentChangePaymentTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected string $model;
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
    protected function putPayment(int $enrollmentId, User | null $user = null){
        return $this->actingAs($user ?? $this->user)
            ->putJson("/enrollments/$enrollmentId/payment");
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $exam = Exam::factory()->inFuture()->create(['center_id' => $this->center->id]);
        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);

        $response = $this->putPayment($enrollment->id);

        $response->assertStatus(200);
    }

    public function test_success_attached_examiner(): void
    {
        $exam = Exam::factory()->inFuture()->create(['center_id' => $this->center->id]);

        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);
        $user = User::factory()->examiner()->create();
        $exam->examiners()->attach($user);
        $response = $this->putPayment($enrollment->id, $user);

        $response->assertOk();
    }

    public function test_fail_has_attempt(): void
    {
        $exam = Exam::factory()->inFuture()->create(['center_id' => $this->center->id]);

        $enrollment = Enrollment::factory()
            ->has(Attempt::factory())
            ->create([
                'exam_id' => $exam->id,
                'center_id' => $this->center->id
            ]);
        $response = $this->putPayment($enrollment->id);

        $response->assertBadRequest();
    }

    public function test_fail_past_exam(): void
    {
        $exam = Exam::factory()->inPast()->create(['center_id' => $this->center->id]);
        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);
        $response = $this->putPayment($enrollment->id);

        $response->assertBadRequest();
    }

    public function test_fail_no_attach_examiner(): void
    {
        $exam = Exam::factory()->inPast()->create(['center_id' => $this->center->id]);
        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);
        $user = User::factory()->examiner()->create();
        $response = $this->putPayment($enrollment->id, $user);

        $response->assertForbidden();
    }

    public function test_access_roles(){
        $exam = Exam::factory()->inFuture()->create(['center_id' => $this->center->id]);

        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);

        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Operator],
            method:'PUT',
            route: fn () => "/enrollments/$enrollment->id/payment",
            center:$this->center
        );
    }
}
