<?php

namespace Tests\Feature\Exam\Manage;

use App\Enums\UserRoles;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Center;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamCancelTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected ExamType $examType;
    protected Address $address;
    protected Exam $exam;

    protected function setUp():void{
            parent::setUp();
            $center = Center::factory()->create();
            $this->user = User::factory()->create();
            $schedulerRole = Role::create([
                'name' => UserRoles::Scheduler
            ]);
            $this->user->roles()->attach($schedulerRole);
            $this->examType = ExamType::factory()->create();
            $this->address = Address::factory()->create();

            Carbon::setTestNow(now());

            $this->exam = Exam::factory()->inFuture($center->time_zone)->create();
            
        }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс фиксации
    }
    public function test_success(): void
    {
        $examId = $this->exam->id;
        $response = $this->actingAs($this->user)
                        ->delete("/exams/$examId", ['cancelledReason' => 'Отменен']);

        $response->assertInertiaFlash('success');
    }

    public function test_fail_with_no_cancel_reason(): void
    {
        
        $examId = $this->exam->id;
        $response = $this->actingAs($this->user)
                        ->delete("/exams/$examId");

        $response->assertInertiaFlashMissing('success');
    }

    public function test_fail_cancel_repeat(): void
    {
        $examId = $this->exam->id;
        $response = $this->actingAs($this->user)
                        ->delete("/exams/$examId", ['cancelledReason' => 'Отменен']);

        $response->assertInertiaFlash('success');

        $response = $this->actingAs($this->user)
                        ->delete("/exams/$examId", ['cancelledReason' => 'Отменен']);

        $response->assertInertiaFlashMissing('success');
    }

    // public function test_fail_no_role_scheduler(): void
    // {
    //     $examId = $this->exam->id;
    //     $user=User::factory()->create();
    //     $response = $this->actingAs($user)
    //                     ->delete("/exams/$examId", ['cancelledReason' => 'Отменен']);

    //     $response->assertInertiaFlashMissing('success');
    // }
}
