<?php

namespace Tests\Feature\Counter;

use App\Actions\Counter\GetSessionNumberAction;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetSessionNumberTest extends TestCase
{
    use RefreshDatabase;
    protected $action;
    protected int $value;
    protected function setUp():void{
        parent::setUp();
        
        $this->action = app(GetSessionNumberAction::class);
        Carbon::setTestNow(Carbon::create(2025, 5, 1, 0, 0, 0));
    }    

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс фиксации
    }
    public function test_success(): void
    {
        $count = 15;
        for($i = 1; $i <= $count; $i++){
            Exam::factory()->create([
                'begin_time'=>Carbon::now()->addDays($i)
            ]);
        }
        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 1)
        ]);
        $sessionNumber = $this->action->execute($exam->begin_time);
        $this->assertEquals($sessionNumber, $count);
    }

    public function test_change_year(): void
    {
        $countFirst = 15;
        for($i = 1; $i <= $countFirst; $i++){
            Exam::factory()->create([
                'begin_time'=>Carbon::now()->addDays($i)
            ]);
        }
        $examFirst = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($countFirst + 1)
        ]);
        $sessionNumber = $this->action->execute($examFirst->begin_time);
        $this->assertEquals($sessionNumber, $countFirst);
        Carbon::setTestNow(Carbon::create(2026, 5, 1, 0, 0, 0));
        $countSecond = 17;
        for($i = 1; $i <= $countSecond; $i++){
            Exam::factory()->create([
                'begin_time'=>Carbon::now()->addDays($i)
            ]);
        }
        $examSecond = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($countSecond + 1)
        ]);
        $sessionNumber = $this->action->execute($examSecond->begin_time);
        $this->assertEquals($sessionNumber, $countSecond);
    }

    public function test_with_cancelled_exam(): void
    {
        $count = 15;
        for($i = 1; $i <= $count; $i++){
            Exam::factory()->create([
                'begin_time'=>Carbon::now()->addDays($i)
            ]);
        }
        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 1),
            'is_cancelled' => true
        ]);
        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 2)
        ]);
        $sessionNumber = $this->action->execute($exam->begin_time);
        $this->assertEquals($sessionNumber, $count);
    }
}
