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
    // public function test_success(){
    //     $student = Student::factory()->create();
    //     $exam = Exam::factory()->create();
    //     $student->exam_code = '0034';
    //     $student->exam_code_expired_at = Carbon::now()->addMinutes(4);
    //     $student->exam_id = $exam->id;
    //     $student->save();
    //     $student->createToken('token');//, ['exam:prepare']
    //     $response = $this  
    //         ->actingAs($student)
    //         ->postJson('exam-attempts',[]);
    //     $response
    //         ->assertStatus(201)
    //         ->assertHeader('content-type', 'application/json');
    //     $this->assertDatabaseCount('exam_attempts', 1);
    // }
}
