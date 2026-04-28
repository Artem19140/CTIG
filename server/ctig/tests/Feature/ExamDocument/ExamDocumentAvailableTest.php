<?php

namespace Tests\Feature\ExamDocument;

use App\Domain\Exam\Guard\ExamGuard;
use App\Domain\ExamDocument\ExamDocumentAvailable;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamDocumentAvailableTest extends TestCase
{
    use RefreshDatabase;
    protected $exception;

    protected function setUp():void{
        parent::setUp();
        Carbon::setTestNow(now());
        $this->exception = BusinessException::class;
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    protected function action(){
        return app(ExamDocumentAvailable::class);
    }

    public function test_fail_codes_cancelled_exam(): void
    {
        $this->expectException($this->exception);
        $this->action()->codes(Exam::factory()->cancelled()->create());
    }

    public function test_codes_calls_guards_once(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create();

        $guard = \Mockery::mock(ExamGuard::class);

        $guard->shouldReceive('ensureNotFinished')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureNotCancelled')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureHasEnrollment')
            ->once()
            ->with($exam);

        $service = new ExamDocumentAvailable($guard);

        $service->codes($exam);
    }

    public function test_list_calls_guards_once(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create();

        $guard = \Mockery::mock(ExamGuard::class);

        $guard->shouldReceive('ensureHasEnrollment')
            ->once()
            ->with($exam);

        $service = new ExamDocumentAvailable($guard);

        $service->list($exam);
    }

    public function test_list_protocol_guards_once(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create();

        $guard = \Mockery::mock(ExamGuard::class);

        $guard->shouldReceive('ensureFinished')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureNotCancelled')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureHasEnrollment')
            ->once()
            ->with($exam);

        $service = new ExamDocumentAvailable($guard);

        $service->protocol($exam);
    }

    public function test_list_results_guards_once(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
            ->inFuture()
            ->create();

        $guard = \Mockery::mock(ExamGuard::class);

        $guard->shouldReceive('ensureFinished')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureNotCancelled')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureHasEnrollment')
            ->once()
            ->with($exam);

        $guard->shouldReceive('ensureAllAttemptsChecked')
            ->once()
            ->with($exam);

        $service = new ExamDocumentAvailable($guard);

        $service->results($exam);
    }
   
}
