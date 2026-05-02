<?php

namespace Tests\Feature\Exam;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamGuardTest extends TestCase
{
    use RefreshDatabase;
    protected $action;
    protected $exception;
    protected int $duration = 90;
    
    protected function setUp():void{
        parent::setUp();
        $this->action = app(ExamGuard::class);
        $this->exception = BusinessException::class;
        Carbon::setTestNow(Carbon::now());
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }

    protected function createExam(array $overrides){
        return Exam::factory()->create(array_merge([
            
        ],$overrides));
    }
    public function test_success_ensure_not_finished(): void
    {
        
        $exam = $this->createExam([
            'begin_time' => Carbon::now()->addMinutes($this->duration - 1)
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
