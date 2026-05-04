<?php

namespace Tests\Feature\ExamDocument;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamCodesGenerationTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->examiner()->create();

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
        $exam = Exam::factory()
            ->has(Enrollment::factory(8))
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
            'begin_time' => Carbon::now()->addDay()
        ]);

        $exam->examiners()->attach($this->user);

        $response = $this
            ->actingAs($this->user)
            ->get(route('exam.documents.codes.available', ['exam' => $exam]));

        $response->assertBadRequest();
    }

    public function test_fail_403(): void
    {
        $exam = Exam::factory()
            ->has(Enrollment::factory())
            ->inFuture()
            ->create();
        $exam->examiners()->attach($this->user);
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get(route('exam.documents.codes', ['exam' => $exam]));
        $response->assertForbidden();
    }
}
