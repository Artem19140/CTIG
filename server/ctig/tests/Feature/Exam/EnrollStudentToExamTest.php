<?php

namespace Tests\Feature\Exam;

use App\Enums\ExamStatusEnum;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;

class EnrollStudentToExamTest extends TestCase
{
    use RefreshDatabase;
        protected User $user;
        protected Student $student;
        protected Exam $exam;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->exam = Exam::factory()->inFuture()->create();
        $this->student = Student::factory()->create();
    }

    public function postEnrollToExam(array $overrides = [],int $examId){
        return $this
                ->actingAs($this->user)
                ->postJson("api/exams/{$examId}/students", $overrides)
                ->assertHeader('content-type', 'application/json');
    }

    public function test_success_enroll(){
        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $this->exam->id);
        $response->assertCreated();
        $this->assertDatabaseCount('exam_student', 1);
    }

    public function test_fail_existing_enrollment(){
        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $this->exam->id);
        $response->assertCreated();

        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $this->exam->id);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('exam_student', 1);
    }

    public function test_fail_student_not_exist(){
        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id+1,
            ], $this->exam->id);
        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('exam_student');
    }

    public function test_fail_exam_not_exist(){
        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $this->exam->id + 1);
        $response->assertNotFound();
        $this->assertDatabaseEmpty('exam_student');
    }

    public function test_fail_under_18(){
        $student = $this->student = Student::factory()->create(['date_birth' => Carbon::now()->subYears(18)->addDay()->toDateString()]);
        $response =  $this->postEnrollToExam([
            'studentId'=>$student->id,
            ], $this->exam->id);
        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('exam_student');
    }

    public function test_fail_full_capacity(){
        $exam = Exam::factory()
            ->has(Student::factory()->count(20))
            ->inFuture()
            ->create([
                'capacity' => 20
            ]);


        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $exam->id);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('exam_student', 20);
    }

    public function test_fail_passed_exam(){
        $exam = Exam::factory()
            ->inPast()
            ->create();
        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $exam->id);
        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('exam_student');
    }

    public function test_fail_student_has_enrollment_to_another_exam_parralell(){
        $exam = Exam::factory()
            ->inFuture()
            ->create();
        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $exam->id);
        $response->assertCreated();

        $response =  $this->postEnrollToExam([
            'studentId'=>$this->student->id,
            ], $this->exam->id);
        $response->assertUnprocessable();

        $this->assertDatabaseCount('exam_student', 1);
    }

    public function test_fail_statuses_not_allowed_to_enroll(){
        
        foreach (ExamStatusEnum::cases() as $status) {
            if($status === ExamStatusEnum::Expected){
                continue;
            }
            $exam = Exam::factory()
                ->inFuture()
                ->create([
                    'status' => $status->value
                ]);
            $response =  $this->postEnrollToExam([
                'studentId'=>$this->student->id,
            ], $exam->id);
            $response->assertUnprocessable();
        }

        $this->assertDatabaseEmpty('exam_student');
    }

    public function test_fail_student_id_is_string(){
        $response =  $this->postEnrollToExam([
            'studentId'=>'124',
            ], $this->exam->id);
        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('exam_student');
    }
}
