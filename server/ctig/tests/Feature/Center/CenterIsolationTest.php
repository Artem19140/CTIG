<?php

namespace Tests\Feature\Center;

use App\Models\Center;
use App\Models\Employee;
use App\Models\Exam;
use App\Policies\ExamPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CenterIsolationTest extends TestCase
{
    use RefreshDatabase;
    public function test_success_center_isolation(): void{
        $centerA = Center::factory()->create();
        $centerB = Center::factory()->create();
        Exam::factory()->create([
            'center_id' => $centerA->id
        ]);

        Exam::factory()->create([
            'center_id' => $centerB->id
        ]);

        $result = Exam::query()
            ->forCenter($centerA->id)
            ->get();
        $this->assertCount(1, $result);
        $this->assertTrue($result->every(fn ($e) => $e->center_id === $centerA->id));
    }

    public function test_center_policy_access(): void{
        $centerA = Center::factory()->create();
        $centerB = Center::factory()->create();
        $examA = Exam::factory()->create([
            'center_id' => $centerA->id
        ]);

        $examB = Exam::factory()->create([
            'center_id' => $centerB->id
        ]);

        $employeeA = Employee::factory()->create([
            'center_id' => $centerA->id
        ]);
        $policy = new ExamPolicy();
        $this->assertTrue($policy->sameCenter($employeeA, $examA));
        $this->assertFalse($policy->sameCenter($employeeA, $examB));
    }
}
