<?php

namespace Tests\Feature\Attempt\AttemptAnswer;

use App\Domain\AttemptAnswer\Handlers\SingleChoiceTaskHandler;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\Answer;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SingleChoiceHanlerTest extends TestCase
{
    use RefreshDatabase;
    protected SingleChoiceTaskHandler $handler;
    protected TaskVariant $taskVariant;
    protected AttemptAnswer $attemptAnswer;
    protected Answer $answer;
    protected function setUp():void{
        parent::setUp();
        $this->taskVariant = new TaskVariant(['id' => 1]);
        $this->answer = new Answer(['task_variant_id' => $this->taskVariant->id, 'id' => 1]);
        $this->taskVariant->setRelation('answers', collect([$this->answer]));
        $this->attemptAnswer = new AttemptAnswer(['id' => 1]);
        $this->handler = app(SingleChoiceTaskHandler::class);
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    
    public function test_success_validation(): void
    {
        $answerRecieved = $this->handler->validate($this->answer->id, $this->taskVariant, $this->attemptAnswer);
        $this->assertEquals($this->answer, $answerRecieved);
    }

    public function test_fail_validation_not_exists_answer(): void
    {
        $this->expectException(AttemptAnswerValidationException::class);
        $this->handler->validate($this->answer->id + 1, $this->taskVariant, $this->attemptAnswer);
        $this->hasLog();
    }

    public function test_fail_validation_string(): void
    {
        $this->expectException(AttemptAnswerValidationException::class);
        $this->handler->validate('d', $this->taskVariant, $this->attemptAnswer);
        $this->hasLog();
    }

    protected function hasLog(){
        Log::spy();
        Log::shouldHaveReceived('channel')
            ->with('single')
            ->once();
    }   
}
