<?php

namespace Tests\Feature\Exam;

use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateExamAttemptTest extends TestCase
{
    protected Student $student;
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->student = Student::factory()->create();
    }
}
