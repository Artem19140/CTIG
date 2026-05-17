<?php

namespace Tests\Feature\Exam;

use App\Domain\Exam\Rules\ValidateExamForSave;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidateExamBeforeSaveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $validator = new ValidateExamForSave();

    }
}
