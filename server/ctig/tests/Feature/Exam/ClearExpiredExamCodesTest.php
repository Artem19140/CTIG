<?php

namespace Tests\Feature\Exam;

use App\Domain\Exam\Action\ClearExpiredExamCodesAction;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearExpiredExamCodesTest extends TestCase
{
    use RefreshDatabase;
    protected ClearExpiredExamCodesAction $action;
    protected function setUp():void{
        parent::setUp();
        $this->action = app(ClearExpiredExamCodesAction::class);
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }    

    public function test_success_expired(): void{
        $enrollment = Enrollment::factory()->create([
            'exam_code' => '123456',
            'exam_code_expired_at' => Carbon::now()->subHour()
        ]);
        $this->action->execute();

        $enrollment->refresh();

        $this->assertNull($enrollment->exam_code);
        $this->assertNotNull($enrollment->exam_code_expired_at);
    }

    public function test_success_not_expired(): void{
        
        $enrollment = Enrollment::factory()->create([
            'exam_code' => '123456',
            'exam_code_expired_at' => Carbon::now()->addHour()
        ]);
        $this->action->execute();
        
        $enrollment->refresh();

        $this->assertNotNull($enrollment->exam_code);
        $this->assertNotNull($enrollment->exam_code_expired_at);
    }
}
