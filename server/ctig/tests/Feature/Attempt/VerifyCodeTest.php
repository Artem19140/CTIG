<?php

namespace Tests\Feature\Attempt;


use App\Domain\Attempt\Action\VerifyCodeAction;
use App\Models\Enrollment;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use App\Domain\ExamDocument\ExamCodesGenerator;

class VerifyCodeTest extends TestCase
{
    use RefreshDatabase;
    protected $action;
    protected $exception;
    protected string $code = '123456';

    protected function setUp():void{
        parent::setUp();
        $this->action = app(VerifyCodeAction::class);
        $this->exception = ValidationException::class;
        
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс
    }

    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        Enrollment::factory()
            ->hasPayment()
            ->examCode($this->code)
            ->examCodeExpiredAt(Carbon::now()->addMinutes(10))
            ->create();
        $result = $this->action->execute($this->code);
        $this->assertInstanceOf(Enrollment::class, $result);
    }

    public function test_fail_no_payment(): void
    {
        $this->expectException($this->exception);
        $enrollment = Enrollment::factory()->noPayment()->create();
        $enrollment->code = $this->code;
        $enrollment->exam_code_expired_at = Carbon::now()->addMinutes(10);
        
        $this->action->execute($this->code);
        
        $this->action->execute($this->code);
    }

    public function test_fail_expired(): void
    {
        $this->expectException($this->exception);
        $this->withoutExceptionHandling();
        Enrollment::factory()
            ->hasPayment()
            ->examCode($this->code)
            ->examCodeExpiredAt(Carbon::now()->subMinutes(10))
            ->create();
        $this->action->execute($this->code);
        
        $this->action->execute($this->code);
    }
}
