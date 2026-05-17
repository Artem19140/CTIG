<?php

namespace Tests\Feature\ForeignNational;

use App\Enums\EmployeeRole;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\Role;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;
use App\Policies\ForeignNationalPolicy;

class ForeignNationalShowAuthorizeTest extends TestCase
{
    
    use RefreshDatabase, RolesAccessCheck;
    protected Center $center;
    protected ForeignNationalPolicy $policy;
    protected array $allowedRoles = [EmployeeRole::SuperAdmin, EmployeeRole::Operator, EmployeeRole::Director];

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center= Center::factory()->create();
        $this->policy = new ForeignNationalPolicy();
        Carbon::setTestNow();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    
    public function test_success_examiner_attached(): void
    {
        $employee = Employee::factory()
            ->examiner()
            ->create([
                'center_id' => $this->center->id
            ]);

        $exam = Exam::factory()->create(['center_id' => $this->center->id]);

        $exam->examiners()
            ->attach($employee);

        $foreignNational = ForeignNational::factory()->create([
                'center_id' => $this->center->id
            ]);
        
        Enrollment::factory()->create([
            'foreign_national_id' => $foreignNational->id,
            'exam_id' => $exam->id,
            'center_id' => $this->center->id
        ]);

        $response = $this->actingAs($employee)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $foreignNational]));

        $response->assertStatus(200);
    }

    public function test_fail_examiner_no_attach(): void{
        $role = new Role();

        $role->name = EmployeeRole::Examiner;
        
        $employee = new Employee();
        
        $employee->setRelation('roles', collect($role));

        $foreignNational = new ForeignNational();

        $this->assertFalse($employee->can('view', $foreignNational));
    }
}
