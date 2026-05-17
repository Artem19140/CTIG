<?php

namespace Tests\Feature\ForeignNational;

use App\Enums\EmployeeRole;
use App\Models\Center;
use App\Models\ForeignNational;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForeignNationalExportTest extends TestCase
{

    use RefreshDatabase;
    protected Employee $actor;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $center = Center::factory()->create();
        $this->actor = Employee::factory()
            ->director()
            ->create(['center_id' => $center->id]);
        Carbon::setTestNow('2026-01-02 10:00:00');
        ForeignNational::factory(10)->create(['center_id' => $center->id]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success_foreign_national_export(): void{
        $this->withoutExceptionHandling();
    
        $response = $this->actingAs($this->actor)
            ->getJson("foreign-nationals/export?dateFrom=2026-01-02&dateTo=2026-01-03");

        $response->assertOk();

        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );

        $this->assertStringContainsString('attachment', 
            $response->headers->get('Content-Disposition')
        );
    }

    public function test_fail_foreign_national_export_diff_centers(): void{
        $employee = Employee::factory()->director()->create();
        $response = $this->actingAs($employee)
            ->getJson("foreign-nationals/export/available?dateFrom=2026-01-02&dateTo=2026-01-03");
        $response->assertBadRequest();
    }

    public function test_no_access_roles_foreign_national_export(){
        $allowedRoles = EmployeeRole::except(
            EmployeeRole::SuperAdmin, 
            EmployeeRole::Director
        );
        foreach($allowedRoles as $role){
            $employee = Employee::factory()
                ->withRole($role)
                ->create();
            $this->assertFalse(
                $employee->can('export', ForeignNational::class),
                "Role {$role->value} should not be allowed"
            );
        }
    }
}
