<?php

namespace Tests\Feature\ExamDocument;

use App\Domain\ExamDocument\ExamDocumentAvailableResolver;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamDocumentAvailableResolverTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp():void{
        parent::setUp();
        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    protected function action(){
        return app(ExamDocumentAvailableResolver::class);
    }


    public function test_cancelled_exam(): void
    {
        $exam = $this->createMock(Exam::class);
        $exam->method('isCancelled')->willReturn(true);
        $result = $this->action()->resolve($exam);

        $this->assertTrue($result['list']['available']);
        $this->assertFalse($result['codes']['available']);
        $this->assertFalse($result['protocol']['available']);
        $this->assertFalse($result['results']['available']);
    }

    public function test_pending_exam(): void
    {
        $exam = $this->createMock(Exam::class);
        $exam->method('isCancelled')->willReturn(false);
        $exam->method('isPending')->willReturn(true);
        $result = $this->action()->resolve($exam);

        $this->assertFalse($result['protocol']['available']);
        $this->assertFalse($result['results']['available']);
    }

    public function test_no_enrollment_exam(): void
    {
        $exam = Exam::factory()->make([
            'enrollments_count' => 0,
        ]);

        $exam->setAttribute('has_attempts', false);
        $exam->setAttribute('has_active_attempts', false);
        $exam->setAttribute('has_unchecked_attempts', false);

        $exam = \Mockery::mock($exam)->makePartial();

        $exam->shouldReceive('isCancelled')->andReturn(false); 
        $exam->shouldReceive('isPending')->andReturn(false); 
        $exam->shouldReceive('canGenerateCodes')->andReturn(false);

        $result = $this->action()->resolve($exam);

        $this->assertFalse($result['list']['available']);
        $this->assertFalse($result['codes']['available']);
        $this->assertFalse($result['protocol']['available']);
        $this->assertFalse($result['results']['available']);
    }

    public function test_no_attempts_exam(): void
    {
        $exam = Exam::factory()->make([
            'enrollments_count' => 10,
        ]);

        $exam->setAttribute('has_attempts', false);
        $exam->setAttribute('has_active_attempts', false);
        $exam->setAttribute('has_unchecked_attempts', false);

        $exam = \Mockery::mock($exam)->makePartial();

        $exam->shouldReceive('isCancelled')->andReturn(false); 
        $exam->shouldReceive('isPending')->andReturn(false); 
        $exam->shouldReceive('canGenerateCodes')->andReturn(true);

        $result = $this->action()->resolve($exam);

        $this->assertTrue($result['list']['available']);
        $this->assertTrue($result['codes']['available']);
        $this->assertFalse($result['protocol']['available']);
        $this->assertFalse($result['results']['available']);
    }

    public function test_active_attempts_exam(): void
    {
        $exam = Exam::factory()->make([
            'enrollments_count' => 10,
        ]);

        $exam->setAttribute('has_attempts', true);
        $exam->setAttribute('has_active_attempts', true);
        $exam->setAttribute('has_unchecked_attempts', false);

        $exam = \Mockery::mock($exam)->makePartial();

        $exam->shouldReceive('isCancelled')->andReturn(false); 
        $exam->shouldReceive('isPending')->andReturn(false); 
        $exam->shouldReceive('canGenerateCodes')->andReturn(true);

        $result = $this->action()->resolve($exam);

        $this->assertTrue($result['list']['available']);
        $this->assertTrue($result['codes']['available']);
        $this->assertFalse($result['protocol']['available']);
        $this->assertFalse($result['results']['available']);
    }

    public function test_unchecked_attempts_exam(): void
    {
        $exam = Exam::factory()->make([
            'enrollments_count' => 10,
        ]);

        $exam->setAttribute('has_attempts', true);
        $exam->setAttribute('has_active_attempts', false);
        $exam->setAttribute('has_unchecked_attempts', true);

        $exam = \Mockery::mock($exam)->makePartial();

        $exam->shouldReceive('isCancelled')->andReturn(false); 
        $exam->shouldReceive('isPending')->andReturn(false); 
        $exam->shouldReceive('canGenerateCodes')->andReturn(true);

        $result = $this->action()->resolve($exam);

        $this->assertTrue($result['list']['available']);
        $this->assertTrue($result['codes']['available']);
        $this->assertTrue($result['protocol']['available']);
        $this->assertFalse($result['results']['available']);
    }

    public function test_exam_results_ready(): void
    {
        $exam = Exam::factory()->make([
            'enrollments_count' => 10,
        ]);

        $exam->setAttribute('has_attempts', true);
        $exam->setAttribute('has_active_attempts', false);
        $exam->setAttribute('has_unchecked_attempts', false);

        $exam = \Mockery::mock($exam)->makePartial();

        $exam->shouldReceive('isCancelled')->andReturn(false); 
        $exam->shouldReceive('isPending')->andReturn(false); 
        $exam->shouldReceive('canGenerateCodes')->andReturn(true);

        $result = $this->action()->resolve($exam);

        $this->assertTrue($result['list']['available']);
        $this->assertTrue($result['codes']['available']);
        $this->assertTrue($result['protocol']['available']);
        $this->assertTrue($result['results']['available']);
    }
}
