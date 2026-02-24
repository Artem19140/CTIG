<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AnswerCheckService;
use App\Enums\TaskType;

class AutoCheckAnswerTest extends TestCase
{
    protected AnswerCheckService $checker;

    protected function setUp(): void
    {
        $this->checker = new AnswerCheckService();
    }

    public function testSingleChoiceCorrect()
    {
        $answers = ['A'];
        $rightAnswers = ['A'];
        $this->assertTrue($this->checker->check($answers, $rightAnswers, TaskType::SingleChoice));
    }

    public function testSingleChoiceIncorrect()
    {
        $answers = ['B'];
        $rightAnswers = ['A'];
        $this->assertFalse($this->checker->check($answers, $rightAnswers, TaskType::SingleChoice));
    }

    public function testTextInputCorrect()
    {
        $answers = ['Answer'];
        $rightAnswers = ['answer', 'other'];
        $this->assertTrue($this->checker->check($answers, $rightAnswers, TaskType::TextInput));
    }

    public function testTextInputIncorrect()
    {
        $answers = ['wrong'];
        $rightAnswers = ['answer', 'other'];
        $this->assertFalse($this->checker->check($answers, $rightAnswers, TaskType::TextInput));
    }

    public function testTextInputMultipleAnswers()
    {
        $answers = ['answer', 'other'];
        $rightAnswers = ['answer', 'other'];
        $this->assertFalse($this->checker->check($answers, $rightAnswers, TaskType::TextInput));
    }
}