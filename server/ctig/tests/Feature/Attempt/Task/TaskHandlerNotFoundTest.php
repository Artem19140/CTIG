<?php

namespace Tests\Feature\Attempt\Task;

use App\Domain\AttemptAnswer\Resolvers\TaskHandlerResolver;
use App\Enums\TaskType;
use App\Exceptions\Task\TaskHandlerNotFoundException;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TaskHandlerNotFoundTest extends TestCase
{
    public function test_example(): void
    {
        $task = new Task();
        $task->type = TaskType::Speaking;
        $this->expectException(TaskHandlerNotFoundException::class);
        
        app(taskHandlerResolver::class)->resolve($task);
        Log::spy();
        Log::shouldHaveReceived('channel')
            ->with('single')
            ->once();
    }
}
