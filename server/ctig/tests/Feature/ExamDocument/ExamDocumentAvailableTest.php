<?php

namespace Tests\Feature\ExamDocument;

use App\Domain\ExamDocument\ExamDocumentAvailable;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Enrollment;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamDocumentAvailableTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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

    public function test_fail_codes_no_enrollment(): void
    {
        $this->expectException($this->exception);
        $this->action()->codes(Exam::factory()->inFuture()->create());
    }

    public function test_fail_codes_past_exam(): void
    {
        $this->expectException($this->exception);
        $this->action()
        ->codes(Exam::factory()
            ->inPast()->create()
        );
    }

    public function test_fail_codes_too_early_gengeration(): void
    {
        $this->expectException($this->exception);
        $this->action()->codes(Exam::factory()->create(['begin_time_utc' => Carbon::now()->addDay()]));
    }

    public function test_fail_protocol_not_finished_exam(): void
    {
        $this->expectException($this->exception);
        $this->action()->protocol(Exam::factory()->inFuture()->create());
    }

    public function test_fail_statement_not_all_attempts_checked(): void
    {
        $this->expectException($this->exception);
        
        $exam = Exam::factory()
            ->inPast()
            ->create();
        $enrollment = Enrollment::factory()->create(['exam_id' => $exam->id]);
        Attempt::factory()
            ->status(AttemptStatus::Finished)
            ->create([
                'exam_id' => $exam->id,
                'enrollment_id' => $enrollment->id
            ]);
        $this->action()->statement($exam);
    }
}
