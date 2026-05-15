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
    protected Exam $exam;
    
    protected function setUp():void{
        parent::setUp();
        $this->action = app(ExamGuard::class);
        $this->exception = BusinessException::class;
        $this->exam = new Exam();
        Carbon::setTestNow(Carbon::now());
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success_ensure_not_finished(): void
    {
        $this->exam->end_time = Carbon::now()->addMinutes(20);
        $this->action->ensureNotFinished($this->exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_not_finished(): void
    {
        $this->expectException($this->exception);
        $this->exam->end_time = Carbon::now()->subMinutes(20);
        $this->action->ensureNotFinished($this->exam);
    }

    public function test_success_ensure_finished(){
        $this->exam->end_time = Carbon::now()->subMinutes(20);
        $this->action->ensureFinished($this->exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_finished(){
        $this->expectException($this->exception);
        $this->exam->end_time = Carbon::now()->addMinutes(20);
        $this->action->ensureFinished($this->exam);
    }

    public function test_sucess_ensure_going(): void
    {
        $this->exam->begin_time = Carbon::now()->subMinutes(20);
        $this->exam->end_time = Carbon::now()->addMinutes(20);
        $this->action->ensureGoing($this->exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_going(){
        $this->expectException($this->exception);
        $this->exam->begin_time = Carbon::now()->subMinutes(40);
        $this->exam->end_time = Carbon::now()->subMinutes(20);
        $this->action->ensureGoing($this->exam);
    }

    public function test_fail_ensure_not_cancelled(): void
    {
        $this->exam->cancelled_at = Carbon::now()->subMinutes(20);
        $this->expectException($this->exception);
        $this->action->ensureNotCancelled($this->exam);
    }
}
