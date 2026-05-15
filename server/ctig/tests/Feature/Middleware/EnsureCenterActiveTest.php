<?php

namespace Tests\Feature\Middleware;

use App\Models\Center;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureCenterActiveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_success(): void
    {
        $center = Center::factory()->notActive()->create();
        
        $employee = Employee::factory()->hasChangePassword()->create([
            'center_id' => $center->id
        ]);

        $response = $this->actingAs($employee)
            ->get('/exams');
        $this->assertGuest('web');
        $response->assertRedirectToRoute('login');
    }
}
