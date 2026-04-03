<?php

namespace Tests\Feature\Attempt;

use App\Actions\Attempt\Create\VerifyCodeAction;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
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
        $exam = Exam::factory()->create(['begin_time_utc' => Carbon::now()]);
        $foreignNational = ForeignNational::factory()->create([
            'exam_code' => $this->code,
            'exam_code_expired_at' => Carbon::now()->addMinute(),
            'exam_id' => $exam->id
        ]);
        $user = User::factory()->create();
        $exam->foreignNationals()->attach($foreignNational->id, [
            'has_payment' => true,
            'reg_number' => 123456,
            'creator_id' => $user->id,
            'organization_id' => $user->organization_id,
        ]);
        
        $result = $this->action->execute($this->code);
        $this->assertInstanceOf(ForeignNational::class, $result);
    }

    public function test_fail_no_payment(): void
    {
        $this->expectException($this->exception);
        $exam = Exam::factory()->create(['begin_time_utc' => Carbon::now()]);
        $foreignNational = ForeignNational::factory()->create([
            'exam_code' => $this->code,
            'exam_code_expired_at' => Carbon::now()->addMinute(),
            'exam_id' => $exam->id
        ]);
        $user = User::factory()->create();
        $exam->foreignNationals()->attach($foreignNational->id, [
            'has_payment' => false,
            'reg_number' => 123456,
            'creator_id' => $user->id,
            'organization_id' => $user->organization_id,
        ]);
        
        $this->action->execute($this->code);
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
