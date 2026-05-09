<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
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
    public function test_success(): void{
        $user = User::factory()
            ->operator()
            ->create([
            'password' => '1234567890',
            'has_to_change_password' => true
        ]);

        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);
        $newPassword = '123456789';
        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticated('web');
        $response->assertRedirect(route('password.change'));
        $response = $this->actingAs($user)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ]);
        $response->assertRedirectToRoute('foreign-nationals.index');
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_fail_no_flag_change_password(): void{
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
        $newPassword = '123456789';
        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticated('web');
        $response = $this->actingAs($user)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ]);
        $response->assertForbidden();
        $user->refresh();
        $this->assertFalse(Hash::check($newPassword, $user->password));
    }

    public function test_fail_not_equal_password(): void{
        $user = User::factory()
            ->operator()
            ->create([
            'password' => '1234567890',
            'has_to_change_password' => true
        ]);

        $response = $this->post('/login',[
            'email' => $user->email,
            'password' => '1234567890'
        ]);
        $newPassword = '123456789';
        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticated('web');
        $response = $this->actingAs($user)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword.'1'
            ]);
        $response->assertUnprocessable();
        $user->refresh();
        $this->assertFalse(Hash::check($newPassword, $user->password));
    }

}
