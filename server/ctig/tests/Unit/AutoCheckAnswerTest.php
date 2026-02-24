<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Actions\Attempt\AutoCheckAnswerAction;
use App\Enums\TaskType;

class AutoCheckAnswerTest extends TestCase
{
    protected AutoCheckAnswerAction $checker;

    protected function setUp(): void
    {
        $this->checker = new AutoCheckAnswerAction();
    }

    public function testSingleChoiceCorrect()
    {
        $answers = ['A'];
        $rightAnswers = ['A'];
        $this->assertTrue($this->checker->execute($answers, $rightAnswers, TaskType::SingleChoice));
    }

    public function testSingleChoiceIncorrect()
    {
        $answers = ['B'];
        $rightAnswers = ['A'];
        $this->assertFalse($this->checker->execute($answers, $rightAnswers, TaskType::SingleChoice));
    }

    public function testTextInputCorrect()
    {
        $answers = ['Answer'];
        $rightAnswers = ['answer', 'other'];
        $this->assertTrue($this->checker->execute($answers, $rightAnswers, TaskType::TextInput));
    }

    public function testTextInputIncorrect()
    {
        $answers = ['wrong'];
        $rightAnswers = ['answer', 'other'];
        $this->assertFalse($this->checker->execute($answers, $rightAnswers, TaskType::TextInput));
    }

    public function testTextInputMultipleAnswers()
    {
        $answers = ['answer', 'other'];
        $rightAnswers = ['answer', 'other'];
        $this->assertFalse($this->checker->execute($answers, $rightAnswers, TaskType::TextInput));
    }
}