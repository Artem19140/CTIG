<?php

namespace Tests\Feature\Auth;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_employee_can_logout(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs($employee);

        $response = $this->post(route('logout'));

        $response->assertRedirectToRoute('login');

        $this->assertGuest(); 

        $response->assertSessionHasNoErrors();
    }
}
