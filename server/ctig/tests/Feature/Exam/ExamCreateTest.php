<?php

namespace Tests\Feature\Exam;

use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Center;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamCreateTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;
    protected ExamType $examType;
    protected Address $address;
    protected Employee $examiner;
    protected Center $center;
    protected RolesSeeder $seed;
    protected function setUp():void{
        parent::setUp();
        $this->center = Center::factory()->create();
        $this->seed(RolesSeeder::class);
        
        $this->examiner = Employee::factory()->examiner()->create(['center_id' => $this->center->id]);

        $this->employee = Employee::factory()->scheduler()->create(['center_id' => $this->center->id]);

        $this->examType = ExamType::factory()->create();

        $this->address = Address::factory()->create(['center_id' => $this->center->id]);

        Carbon::setTestNow(
            Carbon::now()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    protected function postExam(array $overrides = []){
        return $this->actingAs($this->employee)
            ->postJson('/exams', $this->examBody($overrides));
    }

    protected function examBody(array $overrides = []){
        $fixedDate = Carbon::now($this->employee->time_zone)->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS)->addMinute()->format('Y-m-d');
        $fixedTime = Carbon::now($this->employee->time_zone)->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS)->addMinute()->format('H:i');
        return array_merge([
            'date' =>$fixedDate,
            'time' => $fixedTime,
            'examTypeId' => $this->examType->id,
            'addressId'=> $this->address->id,
            'capacity' => $this->address->max_capacity,
            'examiners' => [$this->examiner->id],
            'comment' => ''
        ], $overrides);
    }

    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->postExam();
        
        $response->assertOk();

        $this->assertDatabaseCount('exams', 1);
    }

    public function test_success_different_addresses(): void
    {
        $response = $this->postExam();
        $response->assertOk();
        $examiner = Employee::factory()->examiner()->create();

        $address = Address::factory()->create();

        $response = $this->postExam([
            'addressId' => $address->id,
            'examiners' => [$examiner->id],
            'capacity' => $address->max_capacity,
        ]);
        $response->assertOk();
        $this->assertDatabaseCount('exams', 2);
    }

    public function test_fail_in_past(): void
    {
        $pastDateTime = Carbon::now($this->employee->time_zone)->subHour();

        $response = $this->postExam([
            'date' => $pastDateTime->format('Y-m-d'),
            'time' => $pastDateTime->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS)->subMinute()->format('H:i')
        ]);
        
        $response->assertUnprocessable();

        $this->assertDatabaseEmpty('exams');
    }

    public function test_fail_with_busy_tester(): void
    {
        $response = $this->postExam();
        $response->assertOk();

        $address = Address::factory()->create();

        $response = $this->postExam([
            'addressId' => $address->id,
            'capacity' => $address->max_capacity
        ]);
        $response->assertBadRequest();

        $this->assertDatabaseCount('exams', 1);
    }

    public function test_fail_with_no_role_examiner(): void
    {   
        $response = $this->postExam([
            'examiners' => [$this->employee->id]
        ]);
        $response->assertBadRequest();
        $this->assertDatabaseEmpty('exams');
    }

    public function test_fail_more_than_max_capacity(): void
    {   
        $response = $this->postExam([
            'capacity' => $this->address->max_capacity + 1
        ]);
        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('exams');
    }

    public function test_fail_not_active_address(): void
    {   
        $address = Address::factory()->create(['is_active' => false]);
        $response = $this->postExam([
            'addressId' => $address->id
        ]);
        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('exams');
    }

    public function test_fail_not_active_examiner(): void
    {   
        $employee = Employee::factory()->examiner()->notActive()->create(['center_id' => $this->center->id]);

        $response = $this->postExam([
            'examiners' => [$employee->id]
        ]);
        $response->assertBadRequest();
        $this->assertDatabaseEmpty('exams');
    }
}
