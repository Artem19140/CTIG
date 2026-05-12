<?php

namespace Tests\Feature\Attempt;


use App\Domain\Attempt\Action\VerifyCodeAction;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

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
        Carbon::setTestNow(); 
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
        $enrollment = new Enrollment();
        $enrollment->has_payment = false;
        $enrollment->code = $this->code;
        $enrollment->exam_code_expired_at = Carbon::now()->addMinutes(10);
        
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
    }

    public function test_fail_used_code(): void
    {
        $this->expectException($this->exception);
        $enrollment = new Enrollment();
        $enrollment->has_payment = true;
        $enrollment->code = $this->code;
        $enrollment->exam_code_used_at = Carbon::now();
        $enrollment->exam_code_expired_at = Carbon::now()->addMinutes(10);
        
        $this->action->execute($this->code);
        Log::spy();
        Log::shouldHaveReceived('warning')
            ->once();
    }
}
