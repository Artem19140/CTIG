<?php

namespace Tests\Feature\Exam;

use App\Enums\UserRoles;
use App\Models\Exam;
use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class ExamCancelTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;

    protected Exam $exam;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->scheduler()->create(['center_id' => $this->center->id]);

        Carbon::setTestNow(now());

        $this->exam = Exam::factory()->inFuture($this->center->time_zone)->create(['center_id' => $this->center->id]);
        
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
        $response = $response =$this->postCancell($this->exam->id);

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

    public function test_access_roles(){
        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Scheduler],
            method:'DELETE',
            route:fn () => route('exams.destroy', [
                'exam' => Exam::factory()->inFuture()->create(['center_id' => $this->center->id]),
                'cancelledReason' => 'Отменен'
            ]),
            expectedCode:204,
            center:$this->center
        );
    }
}
