<?php

namespace Tests\Feature\Attempt;

use App\Models\Attempt;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptLifecycleTest extends TestCase
{
    use RefreshDatabase;
    protected User  $user;
    protected Center $center;
    
    protected function setUp():void{
        parent::setUp();
        $this->center = Center::factory()->create();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->examiner()->create();
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    // public function test_success_start(): void
    // {
    //     $this->withoutExceptionHandling();

    //     $foreignNational = ForeignNational::factory()->create();

    //     $exam = Exam::factory()->inFuture()->create(['center_id' => $this->center->id]);
    //     $enrollment = Enrollment::factory()->create([
    //         'exam_id' => $exam->id,
    //         'foreign_national_id' => $foreignNational->id,
    //         'center_id' => $this->center->id
    //     ]);
    //     $attempt = Attempt::factory()
    //         ->pending()
    //         ->create([
    //             'exam_id' => $exam->id,
    //             'foreign_national_id' => $foreignNational->id,
    //             'center_id' => $this->center->id,
    //             'enrollment_id' => $enrollment->id
    //         ]);
    //     $response = $this->actingAs($foreignNational)
    //         ->put(route('attempts.start', ['attempt' => $attempt]));
            
    //     $response->assertRedirectToRoute('attempts.show', ['attempt' => $attempt]);
    // }
}
