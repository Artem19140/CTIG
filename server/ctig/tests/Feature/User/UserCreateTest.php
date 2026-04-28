<?php

namespace Tests\Feature\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Log;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $seeder = RolesSeeder::class;
    protected Role $superAdminRole;
    protected Role $orgAdminRole;
    protected User $user;

    protected function setUp():void{
        parent::setUp(); 
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->orgAdmin()->create();
        $this->superAdminRole = Role::where('name', UserRoles::SuperAdmin)->first();
        $this->orgAdminRole = Role::where('name', UserRoles::OrgAdmin)->first();
        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    protected function userBody(array $overrrides = []):array{
        return array_merge([
            'surname' => fake()->name(),
            'name' => fake()->name(),
            'patronymic' => fake()->name(),
            'jobTitle' => 'Сотрудник ЦТИГ',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'roles' => [],
            'email' => 'unique@bk.ru'
        ], $overrrides);
    }

    protected function postUser(User $actingAs, array $overrrides = []){
        return $this->actingAs($actingAs)
        ->postJson('/employees',$this->userBody($overrrides));
    }


    public function test_success(): void
    {
        $this->withoutExceptionHandling();

        $role = Role::where('name', UserRoles::Operator)->first();

        $response = $this->postUser($this->user, ['roles' => [$role->id]]);

        $response->assertStatus(200);
    }
    public function test_success_org_admin_creating(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->postUser($superAdmin, ['roles' => [$this->orgAdminRole->id]]);
        $response->assertOk();
    }

    public function test_fail_403_org_admin_creating(): void
    {
        $operator = User::factory()->operator()->create();
        $response = $this->postUser($operator, ['roles' => [$this->orgAdminRole->id]]);
        $response->assertForbidden();
    }

    public function test_fail_403_super_admin_creating(): void
    {
        $response = $this->postUser($this->user, ['roles' => [$this->superAdminRole->id]]);
        $response->assertForbidden();
    }

    
}
