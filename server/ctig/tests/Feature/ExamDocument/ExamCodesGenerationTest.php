<?php

namespace Tests\Feature\ExamDocument;

use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamCodesGenerationTest extends TestCase
{
    use RefreshDatabase;
    protected Employee $actor;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create([
            'time_zone' => 'Europe/Moscow'
        ]);
        $this->actor = Employee::factory()->examiner()->create(['center_id' =>  $this->center->id]);

        Carbon::setTestNow(
            Carbon::parse('2026-01-01 10:00:00', $this->center->time_zone)
        );
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success_codes_generation(): void
    {
        $this->withoutExceptionHandling();

        $centerId = $this->center->id;

        $exam = Exam::factory()
            ->has(Enrollment::factory(8)->state(fn () => [
                'center_id' => $this->center->id,
            ]))
            ->create([
                'center_id' =>  $this->center->id,
                'begin_time' => Carbon::now()->addHour()
            ]);

        $exam->examiners()->attach($this->actor);

        $response = $this
            ->actingAs($this->actor)
            ->getJson(route('exam.documents.codes', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_fail_too_early_generation(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory(8)->state(fn () => [
                'center_id' => $this->center->id,
            ]))
            ->create([
                'begin_time' => Carbon::now()->addDay(),
                'center_id' =>  $this->center->id
        ]);

        $exam->examiners()->attach($this->actor);

        $this
            ->actingAs($this->actor)
            ->getJson(route('exam.documents.codes.available', ['exam' => $exam]))
            ->assertBadRequest();
    }

    public function test_fail_examiner_no_attach(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory())
            ->inFuture()
            ->create(['center_id' =>  $this->center->id]);

        $response = $this
            ->actingAs($this->actor)
            ->getJson(route('exam.documents.codes', ['exam' => $exam]));
        $response->assertForbidden();
    }
}
