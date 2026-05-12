<?php

namespace Tests\Feature\Attempt\Task;

use App\Domain\AttemptAnswer\Action\HandleAttemptAnswerAction;
use App\Enums\TaskType;
use App\Exceptions\Task\TaskAnswersNotAllowedException;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Task;
use App\Models\TaskVariant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TaskAnswersNotAllowedTest extends TestCase
{
    public function test_success(): void
    {
        $task = new Task();
        $task->type = TaskType::Speaking;

        $taskVariant = new TaskVariant();

        $taskVariant->setRelation('task', $task);

        $attemptAnswer = new AttemptAnswer();
        $attemptAnswer->setRelation('taskVariant', $taskVariant);

        $attempt = new Attempt();
        
        $attempt->started_at = Carbon::now();

        $this->expectException(TaskAnswersNotAllowedException::class);

        app(HandleAttemptAnswerAction::class)->execute('',$attempt,  $attemptAnswer);

        Log::spy();
        Log::shouldHaveReceived('channel')
            ->with('single')
            ->once();
    }
}
