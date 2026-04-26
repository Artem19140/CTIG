<?php

namespace Tests\Feature\ExamDocument;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Domain\ExamDocument\ExamDocumentAvailable;

class ExamCodesGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected User $user;

    protected function setUp():void{
        parent::setUp();
        $this->user = User::factory()->create();

        Carbon::setTestNow(now());
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс фиксации
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $exam = Exam::factory()
            ->has(Enrollment::factory())
            ->inFuture()
            ->create();
        $exam->examiners()->attach($this->user);

        $response = $this
            ->actingAs($this->user)
            ->get(route('exam.documents.codes', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_fail_too_early_generation(): void
    {
        $exam = Exam::factory()->create([
            'begin_time_utc' => Carbon::now()->addDay()
        ]);

        $exam->examiners()->attach($this->user);

        $response = $this
            ->actingAs($this->user)
            ->get(route('exam.documents.codes.available', ['exam' => $exam]));

        $response->assertBadRequest();
    }
}
