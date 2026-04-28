<?php

namespace Tests\Feature\Enrollment;

use App\Models\Attempt;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChangePaymentTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected string $model;
    protected function setUp():void{
        parent::setUp();
        $this->user = User::factory()->create();
        Carbon::setTestNow(now());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    protected function putPayment(int $enrollmentId){
        return $this->actingAs($this->user)
            ->putJson("/enrollments/$enrollmentId/payment");
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $exam = Exam::factory()->inFuture()->create();
        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id
        ]);
        $response = $this->putPayment($enrollment->id);

        $response->assertStatus(200);
    }

    public function test_fail_has_attempt(): void
    {
        $exam = Exam::factory()->inFuture()->create();
        $enrollment = Enrollment::factory()
            ->has(Attempt::factory())
            ->create([
                'exam_id' => $exam->id
            ]);
        $response = $this->putPayment($enrollment->id);

        $response->assertBadRequest();
    }

    public function test_fail_past_exam(): void
    {
        $exam = Exam::factory()->inPast()->create();
        $enrollment = Enrollment::factory()->create([
            'exam_id' => $exam->id
        ]);
        $response = $this->putPayment($enrollment->id);

        $response->assertBadRequest();
    }
}
