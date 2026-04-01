<?php

namespace Tests\Feature\Attempt;

use App\Actions\Attempt\Create\VerifyCodeAction;
use App\Exceptions\BusinessException;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $this->exception = BusinessException::class;
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс
    }
    public function test_fail_no_foreign_national(): void
    {
        $this->expectException($this->exception);
        
        $this->action->execute($this->code);
    }

    public function test_success(): void
    {
        ForeignNational::factory()->create([
            'exam_code' => $this->code,
            'exam_code_expired_at' => Carbon::now()->addMinute()
        ]);
        
        $result = $this->action->execute($this->code);
        $this->assertInstanceOf(ForeignNational::class, $result);
    }

    public function test_fail_expired(): void
    {
        $this->expectException($this->exception);
        ForeignNational::factory()->create([
            'exam_code' => $this->code,
            'exam_code_expired_at' => Carbon::now()->subMinute()
        ]);
        
        $this->action->execute($this->code);
    }

    public function test_fail_no_expired_at(): void
    {
        $this->expectException($this->exception);
        ForeignNational::factory()->create([
            'exam_code' => $this->code
        ]);
        $this->action->execute($this->code);
    }

    public function test_fail_no_code(): void
    {
        $this->expectException($this->exception);
        ForeignNational::factory()->create([
            'exam_code_expired_at' => Carbon::now()->addMinute()
        ]);
        $this->action->execute($this->code);
    }

    public function test_fail_short_code(): void
    {
        $this->expectException($this->exception);
        ForeignNational::factory()->create([
            'exam_code_expired_at' => Carbon::now()->addMinute(),
            'exam_code' => $this->code
        ]);
        $this->action->execute('12345');
    }

    public function test_fail_long_code(): void
    {
        $this->expectException($this->exception);
        ForeignNational::factory()->create([
            'exam_code_expired_at' => Carbon::now()->addMinute(),
            'exam_code' => $this->code
        ]);
        $this->action->execute('1234567');
    }
}
