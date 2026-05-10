<?php

namespace Tests\Feature\User;

use App\Enums\UserRoles;
use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{

    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected User $activeUser;
    protected Center $center;
    protected function setUp():void{
        parent::setUp(); 
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->orgAdmin()->create(['center_id' => $this->center->id]);
        $this->activeUser = User::factory()->active()->create(['center_id' => $this->center->id]);
        Carbon::setTestNow();
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success(): void{
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)
            ->deleteJson(route('users.destroy', ['user' => $this->activeUser]));
        $this->assertDatabaseHas('users', [
            'id' => $this->activeUser->id,
            'is_active' => false, 
        ]);
        $response->assertNoContent();
    }

    public function test_fail_delete_not_active(): void{
        $notActiveUser = User::factory()->notActive()->create(['center_id' => $this->center->id]);
        $response = $this->actingAs($this->user)
            ->deleteJson(route('users.destroy', ['user' => $notActiveUser]));

        $this->assertDatabaseHas('users', [
            'id' => $notActiveUser->id,
            'is_active' => $notActiveUser->is_active, 
        ]);
        $response->assertBadRequest();
    }

    public function test_fail_another_center_employee(): void{
        
        $userToDelete = User::factory()->create();
        $response = $this->actingAs($this->user)
            ->deleteJson(route('users.destroy', ['user' => $userToDelete]));

        $this->assertDatabaseHas('users', [
            'id' => $userToDelete->id,
            'is_active' => $userToDelete->is_active, 
        ]);
        $response->assertForbidden();
    }

    public function test_success_access(){

        $this->accessRolesCheck(
            allowedRoles:[UserRoles::OrgAdmin],
            method:'DELETE',
            route: fn () => route('users.destroy', ['user' => User::factory()->active()->create(['center_id' => $this->center->id])]),
            center:$this->center,
            expectedCode:204
        );
    }  
}
