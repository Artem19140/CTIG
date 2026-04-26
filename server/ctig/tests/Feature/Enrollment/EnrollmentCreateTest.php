<?php

namespace Tests\Feature\Enrollment;

use App\Enums\CounterKey;
use App\Models\Center;
use App\Models\Counter;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnrollmentCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected User $user;
    protected Exam $exam;
    protected ForeignNational $foreignNational;
    protected string $model;

    protected function setUp():void{
        parent::setUp();
        $this->user = User::factory()->create();
        $this->foreignNational = ForeignNational::factory()->create();
        $this->model = Enrollment::class;
        Counter::create([
            'key' => CounterKey::RegNumKey,
            'value' => 260000,
            'center_id' => Center::inRandomOrder()->first()->id
        ]);
        Carbon::setTestNow(now());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();

        $exam = Exam::factory()->create([
            'begin_time_utc' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->addMinute()
        ]);

        $response = $this->actingAs( $this->user)
            ->postJson('/enrollments',[
                'examId' => $exam->id,
                'foreignNationalId' => $this->foreignNational->id,
                'hasPayment' => true
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount($this->model, 1);
    }

    public function test_fail_exam_past(): void
    {
        $exam = Exam::factory()->create([
            'begin_time_utc' => Carbon::now()->subMinute()
        ]);
        
        $response = $this->actingAs( $this->user)
            ->postJson('/enrollments',[
                'examId' => $exam->id,
                'foreignNationalId' =>  $this->foreignNational->id,
                'hasPayment' => true
            ]);

        $response->assertStatus(400);
        $this->assertDatabaseEmpty($this->model);
    }

    public function test_fail_exam_cancelled(): void
    {

        $exam = Exam::factory()->cancelled()->create([
            'begin_time_utc' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->addMinute()
        ]);
        
        $response = $this->actingAs( $this->user)
            ->postJson('/enrollments',[
                'examId' => $exam->id,
                'foreignNationalId' =>  $this->foreignNational->id,
                'hasPayment' => true
            ]);

        $response->assertStatus(400);
        $this->assertDatabaseEmpty($this->model);
    }

    public function test_fail_closed_enrollment(): void
    {

        $exam = Exam::factory()->cancelled()->create([
            'begin_time_utc' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->subMinute()
        ]);
        
        $response = $this->actingAs( $this->user)
            ->postJson('/enrollments',[
                'examId' => $exam->id,
                'foreignNationalId' =>  $this->foreignNational->id,
                'hasPayment' => true
            ]);

        $response->assertStatus(400);
        $this->assertDatabaseEmpty($this->model);
    }
}
