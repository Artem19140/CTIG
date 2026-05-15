<?php

namespace Tests\Feature\Middleware;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsurePasswordChangeTest extends TestCase
{
    use RefreshDatabase;
    public function test_success(): void
    {
        $employee = Employee::factory()->hasChangePassword()->create();

        $response = $this->actingAs($employee)
            ->get('/exams');

        $response->assertRedirectToRoute('password.change');
    }
}
