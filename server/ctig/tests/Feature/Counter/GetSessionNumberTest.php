<?php

namespace Tests\Feature\Counter;

use App\Domain\Counter\GetSessionNumberQuery;
use App\Models\Attempt;
use App\Models\Enrollment;
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
        
        $this->action = app(GetSessionNumberQuery::class);
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
        $this->createExams($count);

        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 1)
        ]);

        $enrollment = Enrollment::factory()->create(['exam_id' => $exam->id]);
            Attempt::factory()->create([
                'enrollment_id' => $enrollment->id, 
                'exam_id' => $exam->id
            ]);
        $sessionNumber = $this->action->execute($exam->begin_time);
        $this->assertEquals($sessionNumber, $count);
    }

    public function test_change_year(): void
    {
        $countFirst = 15;
        $this->createExams($countFirst);
        $examFirst = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($countFirst + 1)
        ]);

        $sessionNumber = $this->action->execute($examFirst->begin_time);
        $this->assertEquals($sessionNumber, $countFirst);

        Carbon::setTestNow(Carbon::create(2026, 5, 1, 0, 0, 0));
        $countSecond = 17;

        $this->createExams(17);
        
        $examSecond = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($countSecond + 1)
        ]);

        $sessionNumber = $this->action->execute($examSecond->begin_time);

        $this->assertEquals($sessionNumber, $countSecond);
        Carbon::setTestNow();
    }

    public function test_with_cancelled_exam(): void
    {
        $count = 15;
        $this->createExams(15);

        $exam = Exam::factory()->cancelled()->create([
            'begin_time'=>Carbon::now()->addDays($count + 1),
        ]);
        
        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 2)
        ]);

        $enrollment = Enrollment::factory()->create(['exam_id' => $exam->id]);
        Attempt::factory()->create(['enrollment_id' => $enrollment->id, 'exam_id' => $exam->id]);

        $sessionNumber = $this->action->execute($exam->begin_time);
        $this->assertEquals($sessionNumber, $count);
    }

    public function test_with_no_attempts(): void
    {
        $count = 15;
        $this->createExams(15);

        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 1),
        ]);
        
        $exam = Exam::factory()->create([
            'begin_time'=>Carbon::now()->addDays($count + 2)
        ]);
        
        $enrollment = Enrollment::factory()->create(['exam_id' => $exam->id]);
        Attempt::factory()->create(['enrollment_id' => $enrollment->id, 'exam_id' => $exam->id]);

        $sessionNumber = $this->action->execute($exam->begin_time);
        $this->assertEquals($sessionNumber, $count);
    }

    protected function createExams(int $count){
        for($i = 1; $i <= $count; $i++){
            $exam = Exam::factory()->create([
                'begin_time'=>Carbon::now()->addDays($i)
            ]);
            $enrollment = Enrollment::factory()->create(['exam_id' => $exam->id]);
            Attempt::factory()->create(['enrollment_id' => $enrollment->id, 'exam_id' => $exam->id]);
        }
    }
}
