<?php

namespace Tests\Feature\Exam;

use App\Enums\UserRoles;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Center;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamCancelTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;

    protected Exam $exam;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $center = Center::factory()->create();
        $this->user = User::factory()->sheduler()->create();

        Carbon::setTestNow(now());

        $this->exam = Exam::factory()->inFuture($center->time_zone)->create();
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }

    protected function postCancell(int $examId){
        return $this->actingAs($this->user)
            ->deleteJson("/exams/$examId", ['cancelledReason' => 'Отменен']);
    }
    public function test_success(): void
    {
        $examId = $this->exam->id;
        $response =$this->postCancell($examId);

        $response->assertNoContent();
    }

    public function test_fail_with_no_cancel_reason(): void
    {
        
        $examId = $this->exam->id;
        $response = $this->actingAs($this->user)
            ->deleteJson("/exams/$examId");

        $response->assertUnprocessable();
    }

    public function test_fail_cancel_repeat(): void
    {
        $examId = $this->exam->id;
        $response = $response =$this->postCancell($examId);

        $response->assertNoContent();

        $response = $response =$this->postCancell($examId);;

        $response->assertBadRequest();
    }

    public function test_fail_cancel_past_exam(): void
    {
        $exam = Exam::factory()->inPast()->create();
        $response = $response = $this->postCancell($exam->id);;

        $response->assertBadRequest();
    }

    public function test_fail_cancel_going_exam(): void
    {
        $exam = Exam::factory()->now()->create();
        $response = $response = $this->postCancell($exam->id);;

        $response->assertBadRequest();
    }

    // public function test_fail_no_role_scheduler(): void
    // {
    //     $examId = $this->exam->id;
    //     $user=User::factory()->create();
    //     $response = $this->actingAs($user)
    //                     ->delete("/exams/$examId", ['cancelledReason' => 'Отменен']);

    //     $response->assertInertiaFlashMissing('success');
    // }
}
