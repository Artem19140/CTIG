<?php

namespace Tests\Feature\Exam;

use App\Enums\UserRoles;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ForeignNational;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamEnrollmentTest extends TestCase
{   
    use RefreshDatabase;
    protected User $user;
    protected ExamType $examType;
    protected Address $address;
    protected User $examiner;
    protected Exam $exam;
    protected ForeignNational $foreignNational;
    protected int $examId;
    protected function setUp():void{
            parent::setUp();
            $organization = Organization::factory()->create();
            $this->user = User::factory()->create(['organization_id' => $organization->id]);
            $schedulerRole = Role::create([
                'name' => UserRoles::Scheduler
            ]);
            $this->user->roles()->attach($schedulerRole);

            $this->examType = ExamType::factory()->create();
            $this->address = Address::factory()->create(['organization_id' => $organization->id]);

            Carbon::setTestNow(now());

            $exam = Exam::factory()->inFuture($organization->time_zone)->create();
            $this->exam = $exam;
            $this->examId = $exam->id;
            $this->foreignNational = ForeignNational::factory()->create(['organization_id' => $organization->id]);
        }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс фиксации
    }
    public function test_success_create(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->post("/$this->examId/foreign-nationals", [
            'foreignNationalId' => $this->foreignNational->id,
            'hasPayment' => true
        ]);

        $response->assertInertiaFlash('success');
        $response->assertInertiaFlash('redirectUrl');
        $this->assertDatabaseCount('exam_foreign_national', 1);
    }
}
