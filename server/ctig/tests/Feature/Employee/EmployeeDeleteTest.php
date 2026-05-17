<?php

namespace Tests\Feature\Employee;

use App\Models\Center;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeDeleteTest extends TestCase
{

    use RefreshDatabase;
    protected Employee $actor;
    protected Employee $activeEmployee;
    protected Center $center;
    protected function setUp():void{
        parent::setUp(); 

        $this->seed(RolesSeeder::class);

        $this->center = Center::factory()->create();

        $this->actor = Employee::factory()
            ->orgAdmin()
            ->create([
                'center_id' => $this->center->id
            ]);
        $this->activeEmployee = Employee::factory()
            ->active()
            ->create([
                'center_id' => $this->center->id
            ]);
        Carbon::setTestNow();
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success_employee_delete(): void{
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->actor)
            ->deleteJson(route('employees.destroy', ['employee' => $this->activeEmployee]));

        $this->assertDatabaseHas('employees', [
            'id' => $this->activeEmployee->id,
            'is_active' => false, 
        ]);
        
        $response->assertNoContent();
    }

    public function test_fail_delete_not_active(): void{
        $notActiveEmployee = Employee::factory()
            ->notActive()
            ->create([
                'center_id' => $this->center->id
            ]);

        $response = $this->actingAs($this->actor)
            ->deleteJson(route('employees.destroy', ['employee' => $notActiveEmployee]));

        $this->assertDatabaseHas('employees', [
            'id' => $notActiveEmployee->id,
            'is_active' => $notActiveEmployee->is_active, 
        ]);

        $response->assertBadRequest();
    }

    public function test_fail_another_center_employee(): void{
        
        $employeeToDelete = Employee::factory()->create();
        $response = $this->actingAs($this->actor)
            ->deleteJson(route('employees.destroy', ['employee' => $employeeToDelete]));

        $this->assertDatabaseHas('employees', [
            'id' => $employeeToDelete->id,
            'is_active' => $employeeToDelete->is_active, 
        ]);
        $response->assertForbidden();
    }
}
