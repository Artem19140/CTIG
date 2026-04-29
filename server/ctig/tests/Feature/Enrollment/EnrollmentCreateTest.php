<?php

namespace Tests\Feature\Enrollment;

use App\Enums\CounterKey;
use App\Models\Address;
use App\Models\Center;
use App\Models\Counter;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentCreateTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
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

    protected function postEnrollment(int $examId){
        return $this->actingAs( $this->user)
            ->postJson('/enrollments',[
                'examId' => $examId,
                'foreignNationalId' => $this->foreignNational->id,
                'hasPayment' => true
            ]);
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();

        $exam = Exam::factory()->create([
            'begin_time' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->addMinute()
        ]);

        $response = $this->postEnrollment($exam->id);

        $response->assertOk();
        $this->assertDatabaseCount($this->model, 1);
    }

    public function test_fail_exam_past(): void
    {
        $exam = Exam::factory()->create([
            'begin_time' => Carbon::now()->subMinute()
        ]);
        
        $response = $this->postEnrollment($exam->id);

        $response->assertBadRequest();
        $this->assertDatabaseEmpty($this->model);
    }

    public function test_fail_exam_cancelled(): void
    {

        $exam = Exam::factory()->cancelled()->create([
            'begin_time' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->addMinute()
        ]);
        
        $response = $this->postEnrollment($exam->id);

        $response->assertBadRequest();
        $this->assertDatabaseEmpty($this->model);
    }

    public function test_fail_closed_enrollment(): void
    {

        $exam = Exam::factory()->cancelled()->create([
            'begin_time' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->subMinute()
        ]);
        
        $response = $this->postEnrollment($exam->id);

        $response->assertBadRequest();
        $this->assertDatabaseEmpty($this->model);
    }

    public function test_fail_full_enrollment(): void
    {
        $capacity = 8;

        $address = Address::factory()
            ->withCapacity($capacity)
            ->create();

        $exam = Exam::factory()
            ->withCapacity($capacity)
            ->has(Enrollment::factory($capacity))
            ->create([
                'begin_time' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->addMinute(),
                'address_id' => $address->id
            ]);

        
        $response = $this->postEnrollment($exam->id);

        $response->assertBadRequest();
        $this->assertDatabaseCount($this->model, $capacity);
    }

    public function test_fail_more_than_one_enrollment(): void
    {
        $exam = Exam::factory()
            ->create([
                'begin_time' => Carbon::now()->addMinutes(Enrollment::CLOSE_BEFORE_START_MINUTES)->addMinute(),
            ]);

        
        $response = $this->postEnrollment($exam->id);
        $response->assertOk();
        $this->assertDatabaseCount($this->model, 1);

        $response = $this->postEnrollment($exam->id);
        $response->assertBadRequest();
        $this->assertDatabaseCount($this->model, 1);
    }
}
