<?php

namespace Tests\Feature\Enrollment;

use App\Models\Center;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Exam;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnrollmentIsolationTest extends TestCase
{
    use RefreshDatabase;
    public function test_enrollment_isolation(): void
    {
        $this->seed(RolesSeeder::class);
        $center = Center::factory()->create();
        $exam = Exam::factory()->create([
            'center_id' => $center->id
        ]);

        $operator = Employee::factory()
            ->operator()->create([
                'center_id' => $center->id
            ]);
        $examinerA = Employee::factory()
            ->examiner()
            ->create([
                'center_id' => $center->id
            ]);
        $examinerB = Employee::factory()
            ->examiner()
            ->create([
                'center_id' => $center->id
            ]);

        $exam->examiners()->attach($examinerA);
        Enrollment::factory()->create([
            'center_id' => $center->id,
            'exam_id' =>$exam->id
        ]);

        $this->assertCount(1, Enrollment::query()->visibleFor($examinerA)->get());
        $this->assertCount(0, Enrollment::query()->visibleFor($examinerB)->get());
        $this->assertCount(1, Enrollment::query()->visibleFor($operator)->get());
    }
}
