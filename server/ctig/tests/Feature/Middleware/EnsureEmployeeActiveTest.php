<?php

namespace Tests\Feature\Middleware;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureEmployeeActiveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_success(): void
    {
        $employee = Employee::factory()->notActive()->create();

        $response = $this->actingAs($employee)
            ->get('/exams');
        $this->assertGuest('web');
        $response->assertRedirectToRoute('login');
    }
}
