<?php

namespace Tests\Feature\Exam;

use App\Models\Center;
use App\Models\Employee;
use App\Models\Exam;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamIsolationTest extends TestCase
{
    use RefreshDatabase;
    public function test_success_exam_isolaiton(): void{
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
        $this->assertCount(1, Exam::query()->visibleFor($examinerA)->get());
        $this->assertCount(0, Exam::query()->visibleFor($examinerB)->get());
        $this->assertCount(1, Exam::query()->visibleFor($operator)->get());
    }
}
