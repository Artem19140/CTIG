<?php

namespace Tests\Feature\Attempt\AttemptAnswer;

use App\Domain\AttemptAnswer\Handlers\EssayTaskHandler;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EssayTaskTest extends TestCase
{

    protected EssayTaskHandler $handler;
    protected TaskVariant $taskVariant;

    protected AttemptAnswer $attemptAnswer;
    protected int $mark = 1;
    protected function setUp():void{
        parent::setUp();
        $this->taskVariant = new TaskVariant(['id' => 1]);
        $this->attemptAnswer = new AttemptAnswer(['id' => 1]);
        $this->handler = app(EssayTaskHandler::class);
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
        $this->handler->validate(123, $this->attemptAnswer);
        $this->hasLog();
    }

    protected function hasLog(){
        Log::spy();
        Log::shouldHaveReceived('channel')
            ->with('single')
            ->once();
    }   
}
