<?php

namespace Tests\Feature\Attempt;

use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptBanTest extends TestCase
{
    use RefreshDatabase;
    protected User  $user;
    protected function setUp():void{
        parent::setUp();
        
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->examiner()->create();
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
        $user = User::factory()->examiner()->create();
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
        
        $exam = Exam::factory()->inPast()->create();
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id]);
        $response = $this->actingAs($this->user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]));

        $response->assertUnprocessable();
    }

    public function test_fail_ban_twice(): void
    {
        $exam = Exam::factory()->inPast()->create();
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id]);
        $response = $this->actingAs($this->user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);

        $response->assertNoContent();

        $response = $this->actingAs($this->user)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);
        $response->assertBadRequest();
    }
}
