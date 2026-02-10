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

    protected $minutes;


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
                ->postJson("api/exams/{$examId}/codes")
                ->assertHeader('content-type', 'application/json');
    }
    public function test_success_creation(){
        $exam = Exam::factory()
            ->withStudents(3)
            ->create([
                'begin_time' => Carbon::now()->addMinutes(40 - 1),
                'end_time' => Carbon::now()->addHours(2),
            ]);
        $response =  $this->postCreateCodes($exam->id);
        $response->assertStatus(201);
        $this->assertDatabaseCount('exam_codes', 3);
    }

    public function test_fail_cannot_generate_exam_codes_before_allowed_time(){
        $exam = Exam::factory()
            ->withStudents(3)
            ->create([
                'begin_time' => Carbon::now()->addMinutes(40 + 1),
                'end_time' => Carbon::now()->addHours(2),
            ]);
        $response =  $this->postCreateCodes($exam->id);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('exam_codes');
    }

    public function test_fail_passed_exam(){
        $exam = Exam::factory()
            ->inPast()
            ->withStudents(3)
            ->create();
        $response =  $this->postCreateCodes($exam->id);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('exam_codes');
    }

    public function test_fail_exam_not_exist(){
        $response =  $this->postCreateCodes($this->exam->id + 1);
        $response->assertStatus(404);
        $this->assertDatabaseEmpty('exam_codes');
    }

    public function test_fail_no_enrollments_for_exam(){
        $exam = Exam::factory()
            ->inFuture()
            ->create();
        $response =  $this->postCreateCodes($exam->id);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('exam_codes');
    }


}
