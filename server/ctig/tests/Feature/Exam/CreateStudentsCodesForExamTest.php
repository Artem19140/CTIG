<?php

namespace Tests\Feature\Exam;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\ExamStatus;
use App\Models\Student;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Exam;

class CreateStudentsCodesForExamTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected Student $student;
    protected Exam $exam;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->exam = Exam::factory()
            ->inFuture()
            ->withStudents(3)
            ->create();;
        $this->student = Student::factory()->create();
    }

    public function postCreateCodes(int $examId){
        return $this
                ->actingAs($this->user)
                ->postJson("api/exams/{$examId}/codes");
    }
    public function test_success_creation(){
        $minutes = config('exam.code_generation_before_minutes');
        $exam = Exam::factory()
            ->withStudents(3)
            ->create([
                'begin_time' => Carbon::now()->addMinutes($minutes - 1),
                'end_time' => Carbon::now()->addHours(2),
            ]);
        $response =  $this->postCreateCodes($exam->id);
        $response->assertStatus(200) //201
            ->assertHeader('content-type', 'application/pdf');
        
        
    }

    public function test_fail_cannot_generate_exam_codes_before_allowed_time(){
        $minutes = config('exam.code_generation_before_minutes');
        $exam = Exam::factory()
            ->withStudents(3)
            ->create([
                'begin_time' => Carbon::now()->addMinutes($minutes + 1),
                'end_time' => Carbon::now()->addHours(2),
            ]);
        $response =  $this->postCreateCodes($exam->id);
        $response->assertStatus(422);
        $students = $exam->students;
        foreach ($students as $student) {
            $this->assertNull($student->exam_code);
            $this->assertNull($student->exam_code_expired_at);
            $this->assertNull($student->exam_id);
        }
    }

    public function test_fail_passed_exam(){
        $exam = Exam::factory()
            ->inPast()
            ->withStudents(3)
            ->create();
        $response =  $this->postCreateCodes($exam->id);
        $response->assertUnprocessable();
        $students = $exam->students;
        foreach ($students as $student) {
            $this->assertNull($student->exam_code);
            $this->assertNull($student->exam_code_expired_at);
            $this->assertNull($student->exam_id);
        }
    }

    public function test_fail_exam_not_exist(){
        $response =  $this->postCreateCodes($this->exam->id + 1);
        $response->assertStatus(404);

    }

    public function test_fail_no_enrollments_for_exam(){
        $exam = Exam::factory()
            ->inFuture()
            ->create();
        $response =  $this->postCreateCodes($exam->id);
        $response->assertUnprocessable();
        $students = $exam->students;
        foreach ($students as $student) {
            $this->assertNull($student->exam_code);
            $this->assertNull($student->exam_code_expired_at);
            $this->assertNull($student->exam_id);
        }
    }


}
