<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRoles;
use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);        
        Carbon::setTestNow(
            Carbon::now()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    use RefreshDatabase;
    public function test_success_operator(): void
    {
        $user = User::factory()
            ->operator()
            ->create([
                'password' => '1234567890',
                'has_to_change_password' => false
            ]);
        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);
        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticated('web');
        $response->assertRedirectToRoute('exams.index');
    }

    public function test_fail(): void
    {
        $user = User::factory()->create([
            'password' => '1234567890'
        ]);
        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '123456789'
        ]);
        $response->assertRedirectBack();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_fail_not_active_user(): void
    {
        $user = User::factory()
            ->notActive()
            ->create([
                'password' => '1234567890'
            ]);

        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);

       $response->assertSessionHasErrors(['email']);
    }

    public function test_fail_not_active_center(): void
    {
        $center = Center::factory()->notActive()->create();
        $user = User::factory()
            ->create([
                'password' => '1234567890',
                'center_id' => $center->id
            ]);

        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);

       $response->assertSessionHasErrors(['email']);
    }
}
