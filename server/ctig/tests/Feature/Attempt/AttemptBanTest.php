<?php

namespace Tests\Feature\Attempt;

use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttemptBanTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected function setUp():void{
        parent::setUp();
        
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $exam = Exam::factory()->inPast()->create();
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id]);
        $response = $this->actingAs($user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);

        $response->assertNoContent();
    }

    public function test_fail_no_ban_reason(): void
    {
        $user = User::factory()->create();
        $exam = Exam::factory()->inPast()->create();
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id]);
        $response = $this->actingAs($user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]));

        $response->assertUnprocessable();
    }

    public function test_fail_ban_twice(): void
    {
        $user = User::factory()->create();
        $exam = Exam::factory()->inPast()->create();
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id]);
        $response = $this->actingAs($user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);

        $response->assertNoContent();

        $response = $this->actingAs($user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);
        $response->assertBadRequest();
    }
}
