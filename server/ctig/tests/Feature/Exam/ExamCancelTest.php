<?php

namespace Tests\Feature\Exam;

use App\Models\Exam;
use App\Models\Center;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamCancelTest extends TestCase
{
    use RefreshDatabase;
    protected Employee $employee;

    protected Exam $exam;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->employee = Employee::factory()->scheduler()->create(['center_id' => $this->center->id]);

        Carbon::setTestNow(now());

        $this->exam = Exam::factory()->inFuture($this->center->time_zone)->create(['center_id' => $this->center->id]);
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }

    protected function postCancell(int $examId){
        return $this->actingAs($this->employee)
            ->deleteJson("/exams/$examId", ['cancelledReason' => 'Отменен']);
    }
    public function test_success_exam_cancell(): void{
        $response = $this->postCancell($this->exam->id);

        $response->assertNoContent();
    }

    public function test_fail_with_no_cancel_reason(): void{
        
        $response = $this->actingAs($this->employee)
            ->deleteJson("/exams/{$this->exam->id}");

        $response->assertUnprocessable();
    }

    public function test_fail_cancel_repeat(): void
    {
        $response = $response = $this->postCancell($this->exam->id);

        $response->assertNoContent();

        $response = $response =$this->postCancell($this->exam->id);;

        $response->assertBadRequest();
    }

    public function test_fail_cancel_past_exam(): void
    {
        $exam = Exam::factory()->inPast()->create(['center_id' => $this->center->id]);
        $response = $response = $this->postCancell($exam->id);;

        $response->assertBadRequest();
    }

    public function test_fail_cancel_going_exam(): void
    {
        $exam = Exam::factory()->now()->create(['center_id' => $this->center->id]);
        $response = $response = $this->postCancell($exam->id);;

        $response->assertBadRequest();
    }
}
