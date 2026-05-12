<?php

namespace Tests\Feature\Attempt\AttemptAnswer;


use App\Domain\AttemptAnswer\Action\RateAttemptAnswerAction;
use App\Enums\TaskType;
use App\Exceptions\Attempt\AnswerManualCheckException;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Task;
use App\Models\TaskVariant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RateAttemptAnswerTest extends TestCase
{
    protected RateAttemptAnswerAction $action;

    protected AttemptAnswer $attemptAnswer;
    protected Attempt $attempt;
    protected Task $task;
    protected int $mark = 2;
    protected function setUp():void{
        parent::setUp();
        $this->task = new Task();
        $this->task->type = collect(TaskType::manualCheckTypes())->random();
        $this->task->mark = $this->mark;

        $taskVariant = new TaskVariant();
        
        $taskVariant->setRelation('task', $this->task);

        $this->attemptAnswer = new AttemptAnswer();

        $this->attempt = new Attempt();

        

        $this->attemptAnswer->setRelation('attempt', $this->attempt);

        $this->attemptAnswer->setRelation('taskVariant', $taskVariant);

        $this->action = app(RateAttemptAnswerAction::class);
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_fail_mark_more_than_max(): void{
        $this->attempt->finish();
        $this->expectException(AnswerManualCheckException::class);
        $this->action->execute($this->attemptAnswer, $this->mark + 1);

        $this->hasLog();
    }

    public function test_fail_mark_checked_attempt(): void{
        $this->attempt->finish();
        $this->attempt->markAsChecked();
        $this->expectException(AnswerManualCheckException::class);
        $this->action->execute($this->attemptAnswer, $this->mark);
        $this->hasLog();
    }

    public function test_fail_task_no_manual_check(): void{
        $this->attempt->finish();
        $this->task->type = collect(TaskType::autoCheckTypes())->random();
        $this->expectException(AnswerManualCheckException::class);
        $this->action->execute($this->attemptAnswer, $this->mark);
        $this->hasLog();
    }

    public function test_cannot_rate_answer_for_this_task_type_when_attempt_not_finished(): void{
        $this->attempt->finish();
        $this->task->type = 
            collect(TaskType::autoCheckTypes())
            ->filter(function($type){
                return $type !== TaskType::Speaking;
            })
            ->random();
        $this->expectException(AnswerManualCheckException::class);
        $this->action->execute($this->attemptAnswer, $this->mark);
        $this->hasLog();
    }

    protected function hasLog(){
        Log::spy();
        Log::shouldHaveReceived('warning')
            ->once();
    }   
}
