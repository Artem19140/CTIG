<?php

namespace Tests\Feature\ExamDocument;

use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class ExamListGenerationTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected Employee $actor;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->actor = Employee::factory()->examiner()->create(['center_id' => $this->center->id]);

        Carbon::setTestNow(now());
        
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success_examiter_attached(): void
    {
        $this->withoutExceptionHandling();
        $centerId = $this->center->id;
        $exam = Exam::factory()
            ->has(Enrollment::factory(8)->state(fn () => [
                'center_id' => $this->center->id,
            ]))
            ->create(['center_id' =>  $this->center->id]);
        $exam->examiners()->attach($this->actor);
        $response = $this
            ->actingAs($this->actor)
            ->getJson(route('exam.documents.list', ['exam' => $exam]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
