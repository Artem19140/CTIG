<?php

namespace Tests\Feature\Attempt;

use App\Models\Attempt;
use App\Models\Center;
use App\Models\Exam;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptBanTest extends TestCase
{
    use RefreshDatabase;
    protected Employee  $employee;
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->center = Center::factory()->create();
        $this->seed(RolesSeeder::class);
        $this->employee = Employee::factory()->examiner()->create(['center_id' => $this->center->id]);
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

        $exam = Exam::factory()->inPast()->create(['center_id' => $this->center->id]);
        $exam->examiners()->attach($this->employee);
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id, 'center_id' => $this->center->id]);
        $response = $this->actingAs($this->employee)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);

        $response->assertNoContent();
    }

    public function test_fail_no_ban_reason(): void
    {
        
        $exam = Exam::factory()->inPast()->create(['center_id' => $this->center->id]);
        $exam->examiners()->attach($this->employee);
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id, 'center_id' => $this->center->id]);
        $response = $this->actingAs($this->employee)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]));

        $response->assertUnprocessable();
    }

    public function test_fail_ban_twice(): void
    {
        $exam = Exam::factory()->inPast()->create(['center_id' => $this->center->id]);
        $exam->examiners()->attach($this->employee);
        $attempt = Attempt::factory()->create(['exam_id'=>$exam->id, 'center_id' => $this->center->id]);
        $response = $this->actingAs($this->employee)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);

        $response->assertNoContent();

        $response = $this->actingAs($this->employee)
            ->putJson(route('attempts.ban', ['attempt' => $attempt]),[
                'banReason' => 'Есть'
            ]);
        $response->assertBadRequest();
    }
}
