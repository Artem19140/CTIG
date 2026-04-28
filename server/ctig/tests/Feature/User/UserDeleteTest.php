<?php

namespace Tests\Feature\User;

use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{

    use RefreshDatabase;
    protected User $user;
    protected function setUp():void{
        parent::setUp(); 
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->orgAdmin()->create();

        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success(): void{
        $this->withoutExceptionHandling();
        
        $userToDelete = User::factory()->create(['center_id' => $this->user->center_id]);
        $response = $this->actingAs($this->user)
            ->deleteJson(route('users.destroy', ['user' => $userToDelete]));
        $this->assertDatabaseHas('users', [
            'id' => $userToDelete->id,
            'is_active' => false, 
        ]);
        $response->assertNoContent();
    }

    public function test_fail_delete_not_active(): void{
        
        $userToDelete = User::factory()->notActive()->create(['center_id' => $this->user->center_id]);
        $response = $this->actingAs($this->user)
            ->deleteJson(route('users.destroy', ['user' => $userToDelete]));

        $this->assertDatabaseHas('users', [
            'id' => $userToDelete->id,
            'is_active' => $userToDelete->is_active, 
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
}
