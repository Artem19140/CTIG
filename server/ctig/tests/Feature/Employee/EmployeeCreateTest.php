<?php

namespace Tests\Feature\Employee;

use App\Enums\EmployeeRole;
use App\Models\Center;
use App\Models\Role;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeCreateTest extends TestCase
{
    use RefreshDatabase;
    protected $seeder = RolesSeeder::class;
    protected Role $superAdminRole;
    protected Role $orgAdminRole;
    protected Employee $actor;
    protected Center $center;

    protected function setUp():void{
        parent::setUp(); 
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->actor = Employee::factory()->orgAdmin()->create(['center_id' => $this->center->id]);

        $this->superAdminRole = Role::findByEnum(EmployeeRole::SuperAdmin);

        $this->orgAdminRole = Role::findByEnum(EmployeeRole::CenterAdmin);
        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    protected function employeeBody(array $overrrides = []):array{
        return array_merge([
            'surname' => fake()->name(),
            'name' => fake()->name(),
            'patronymic' => fake()->name(),
            'jobTitle' => 'Сотрудник ЦТИГ',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'roles' => [Role::findByEnum(EmployeeRole::Operator)->id],
            'email' => 'unique@bk.ru'
        ], $overrrides);
    }

    protected function postEmployee(Employee $actingAs, array $overrrides = []){
        return $this->actingAs($actingAs)
        ->postJson('/employees',$this->employeeBody($overrrides));
    }


    public function test_success(): void
    {
        $this->withoutExceptionHandling();

        $role = Role::findByEnum(EmployeeRole::Operator);

        $response = $this->postEmployee($this->actor, ['roles' => [$role->id]]);

        $response->assertStatus(200);
    }
    public function test_success_org_admin_creating(): void
    {
        $superAdmin = Employee::factory()->superAdmin()->create();
        $response = $this->postEmployee($superAdmin, ['roles' => [$this->orgAdminRole->id]]);
        $response->assertOk();
    }

    public function test_fail_403_org_admin_creating(): void
    {
        $operator = Employee::factory()->operator()->create();
        $response = $this->postEmployee($operator, ['roles' => [$this->orgAdminRole->id]]);
        $response->assertForbidden();
    }

    public function test_fail_403_super_admin_creating(): void
    {
        $response = $this->postEmployee($this->actor, ['roles' => [$this->superAdminRole->id]]);
        $response->assertForbidden();
    }
}
