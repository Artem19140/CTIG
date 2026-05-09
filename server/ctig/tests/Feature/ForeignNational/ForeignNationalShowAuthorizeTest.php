<?php

namespace Tests\Feature\ForeignNational;

use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForeignNationalShowAuthorizeTest extends TestCase
{
    use RefreshDatabase;
    protected Enrollment $enrollment;
    protected ForeignNational $foreignNational;
    protected Exam $exam;
    
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center= Center::factory()->create();
        $this->foreignNational = ForeignNational::factory()->create(); //['center_id' => $this->center->id]
        $this->exam = Exam::factory()->create(['center_id' => $this->center->id]);
        $this->enrollment = Enrollment::factory()->create([
            'foreign_national_id' => $this->foreignNational->id,
            'exam_id' => $this->exam->id
        ]);
        Carbon::setTestNow();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    
    public function test_success_examiner(): void
    {
        $user = User::factory()->examiner()->create();

        $this->exam->examiners()->attach($user);
        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(200);
    }

    public function test_fail_examiner_no_attach(): void
    {
        $user = User::factory()->examiner()->create();

        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(403);
    }

    public function test_fail_director(): void
    {
        $user = User::factory()->director()->create();

        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(200);
    }

    public function test_fail_operator(): void
    {
        $user = User::factory()->operator()->create();

        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(200);
    }

    public function test_fail_super_admin(): void
    {
        $user = User::factory()->superAdmin()->create();

        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(200);
    }

    public function test_fail_scheduler(): void
    {
        $user = User::factory()->scheduler()->create();

        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(403);
    }

    public function test_fail_org_admin(): void
    {
        $user = User::factory()->orgAdmin()->create();

        $response = $this->actingAs($user)
            ->getJson(route('foreign-nationals.show', ['foreign_national' => $this->foreignNational]));

        $response->assertStatus(403);
    }
}
