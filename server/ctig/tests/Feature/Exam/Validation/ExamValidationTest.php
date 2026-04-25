<?php

namespace Tests\Feature\Exam\Validation;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ForeignNational;
use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamValidationTest extends TestCase
{
    use RefreshDatabase;
    protected $action;
    protected $exception;
    protected ExamType $examType;
    
    protected int $duration = 90;
    
    protected function setUp():void{
        parent::setUp();
        
        $this->action = app(ExamGuard::class);
        $this->exception = BusinessException::class;
        $this->examType = ExamType::factory()->create(['duration' => $this->duration]);
        Carbon::setTestNow(Carbon::now());
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс
    }

    protected function createExam(array $overrides){
        return Exam::factory()->create(array_merge([
            
        ],$overrides));
    }
    public function test_success_ensure_not_finished(): void
    {
        
        $exam = $this->createExam([
            'begin_time_utc' => Carbon::now()->addMinutes($this->duration - 1)
        ]);
        $this->action->ensureNotFinished($exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_not_finished(): void
    {
        $this->expectException($this->exception);
        $exam = Exam::factory()->inPast($this->duration)->create();
        $this->action->ensureNotFinished($exam);
    }

    public function test_success_ensure_finished(){
        $exam = Exam::factory()->inPast($this->duration)->create();
        $this->action->ensureFinished($exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_finished(){
        $this->expectException($this->exception);
        $exam = Exam::factory()->inFuture()->create();
        $this->action->ensureFinished($exam);
    }
    public function test_fail_ensure_finished_going(){
        $this->expectException($this->exception);
        $exam = Exam::factory()->now()->create();
        $this->action->ensureFinished($exam);
    }

    public function test_success_ensure_has_enrollment(){
        $exam = Exam::factory()
            ->hasAttached(
                ForeignNational::factory()->count(3),
                [
                    'reg_number'=>124,
                    'creator_id' => User::factory()->create()->id, 
                    'has_payment'=> true,
                    'center_id' => Center::factory()->create()->id
                ]
            )->create();
        $this->action->ensureHasEnrollment($exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_has_enrollment(){
        $this->expectException($this->exception);
        $exam = Exam::factory()->create();
        $this->action->ensureHasEnrollment($exam);
    }

    public function test_sucess_ensure_going(): void
    {
        $exam = Exam::factory()->now()->create();
        $this->action->ensureGoing($exam);
        $this->assertTrue(true);
    }
    public function test_fail_ensure_going_not_started(): void
    {
        $this->expectException($this->exception);
        $exam = Exam::factory()->inFuture()->create();
        $this->action->ensureGoing($exam);
    }

    public function test_fail_ensure_going_after(): void
    {
        $this->expectException($this->exception);
        $exam = Exam::factory()->inPast($this->duration)->create();
        $this->action->ensureGoing($exam);
        
    }
    public function test_fail_ensure_not_going(): void
    {
        $this->expectException($this->exception);
        $exam = Exam::factory()->now()->create();
        $this->action->ensureNotGoing($exam);
    }

    public function test_fail_ensure_not_cancelled(): void
    {
        $this->expectException($this->exception);
        $exam = Exam::factory()->inFuture()->cancelled()->create();
        $this->action->ensureNotCancelled($exam);
    }
}
