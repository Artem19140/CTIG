<?php

namespace Tests\Feature\Auth;

use App\Models\Center;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
    protected string $password = '1234567890';
    public function test_success_operator(): void
    {
        $employee = Employee::factory()
            ->operator()
            ->create([
                'password' => Hash::make($this->password),
                'has_to_change_password' => false
            ]);
        $response = $this->post('/login',[
            'email' => $employee->email,
            'password' => $this->password
        ]);
        $this->assertAuthenticatedAs($employee);
        $this->assertAuthenticated('web');
        $response->assertRedirectToRoute('foreign-nationals.index');
    }

    public function test_fail_wrong_credentials(): void
    {
        $employee = Employee::factory()->create([
            'password' => $this->password
        ]);
        $response = $this->post('/login',[
            'email' => $employee->email,
            'password' => $this->password.'1'
        ]);
        $response->assertRedirectBack();
        $this->assertGuest('web');
    }

    public function test_fail_not_active_employee(): void
    {
        $employee = Employee::factory()
            ->notActive()
            ->create([
                'password' => Hash::make($this->password)
            ]);

        $response = $this->post('/login',[
            'email' => $employee->email,
            'password' => $this->password
        ]);

       $response->assertSessionHasErrors(['email']);
       $this->assertGuest('web');
    }

    public function test_fail_not_active_center(): void
    {
        $center = Center::factory()->notActive()->create();
        $employee = Employee::factory()
            ->create([
                'password' => Hash::make($this->password),
                'center_id' => $center->id
            ]);

        $response = $this->post('/login',[
            'email' => $employee->email,
            'password' =>$this->password
        ]);

       $response->assertRedirectBack();
       $this->assertGuest('web');
    }
}
