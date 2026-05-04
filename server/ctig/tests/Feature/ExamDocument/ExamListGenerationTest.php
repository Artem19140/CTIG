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

class ExamListGenerationTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->operator()->create();

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

        $response = $this
            ->actingAs($this->user)
            ->get(route('exam.documents.list', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
