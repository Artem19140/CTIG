<?php

namespace Tests\Feature\Auth;

use App\Models\Employee;
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
        $employee = Employee::factory()
            ->operator()
            ->create([
                'has_to_change_password' => true
            ]);

        $newPassword = '123456789';

        $response = $this->actingAs($employee)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ]);
        $response->assertRedirectToRoute('foreign-nationals.index');
        $employee->refresh();
        $this->assertTrue(Hash::check($newPassword, $employee->password));
    }

    public function test_fail_no_flag_change_password(): void{
        $employee = Employee::factory()
            ->operator()
            ->create([
            'has_to_change_password' => false
        ]);

        $newPassword = '123456789';

        $response = $this->actingAs($employee)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ]);

        $response->assertForbidden();
        $employee->refresh();
        $this->assertFalse(Hash::check($newPassword, $employee->password));
    }

    public function test_fail_not_equal_password(): void{
        $employee = Employee::factory()
            ->operator()
            ->create([
                'has_to_change_password' => true
        ]);

        $newPassword = '123456789';

        $response = $this->actingAs($employee)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword.'1'
            ]);
        $response->assertUnprocessable();
        $employee->refresh();
        $this->assertFalse(Hash::check($newPassword, $employee->password));
    }

    public function test_fail_old_password(): void{
        $newPassword = '123456789';

        $employee = Employee::factory()
            ->operator()
            ->create([
                'has_to_change_password' => true,
                'password' => Hash::make($newPassword)
        ]);

        $response = $this->actingAs($employee)
            ->postJson('password/change', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ]);

        $response->assertUnprocessable();
        $employee->refresh();
        $this->assertTrue(Hash::check($newPassword, $employee->password));
    }

    public function test_fail_super_admin(): void{
        $newPassword = '123456789';

        $employee = Employee::factory()
            ->superAdmin()
            ->create([
                'has_to_change_password' => true,
                'password' => Hash::make($newPassword)
        ]);

        $response = $this->actingAs($employee)
            ->postJson('password/change', [
                'password' => $newPassword.'1',
                'password_confirmation' => $newPassword.'1'
            ]);

        $response->assertForbidden();
        $employee->refresh();
        $this->assertTrue(Hash::check($newPassword, $employee->password));
    }
}
