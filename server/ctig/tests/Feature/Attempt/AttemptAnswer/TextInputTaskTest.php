<?php

namespace Tests\Feature\Attempt\AttemptAnswer;

use App\Domain\AttemptAnswer\Handlers\TextInputTaskHandler;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\Answer;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TextInputTaskTest extends TestCase
{
    protected TextInputTaskHandler $handler;
    protected TaskVariant $taskVariant;
    protected Answer $answer;
    protected AttemptAnswer $attemptAnswer;
    protected int $mark = 1;
    protected string $content = 'sdf';
    protected function setUp():void{
        parent::setUp();
        $this->taskVariant = new TaskVariant(['id' => 1]);

        $this->answer = new Answer(['id' => 1, 'content' => $this->content]);
        $this->taskVariant->setRelation('answers', collect([$this->answer]));

        $this->attemptAnswer = new AttemptAnswer(['id' => 1]);
        $this->handler = app(TextInputTaskHandler::class);
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    public function test_success_validation(): void
    {
        $answer = 'dsdfsdfdsf';
        $recievedAnswer =  $this->handler->validate($answer, $this->attemptAnswer);
        $this->assertEquals($answer, $recievedAnswer);
    }

    public function test_fail_validation_number(): void
    {
        $this->expectException(AttemptAnswerValidationException::class);
        $this->handler->validate(1234, $this->attemptAnswer);
        $this->hasLog();
    }

    public function test_calculate_mark_correct_answer(): void
    {
        $mark = $this->handler->calculateMark($this->content, $this->taskVariant);
        $this->assertEquals($mark, $this->mark);
    }

    public function test_calculate_mark_not_correct_answer(): void
    {
        $mark = $this->handler->calculateMark('123svsefr2', $this->taskVariant);
        $this->assertEquals($mark, 0);
    }

    protected function hasLog(){
        Log::spy();
        Log::shouldHaveReceived('channel')
            ->with('single')
            ->once();
    }   
}
