<?php

namespace Tests\Feature\Exam;

use App\Models\Center;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamShowAuthorizeTest extends TestCase
{
    use RefreshDatabase;
    protected Exam $exam;
    protected Center $center;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = $center = Center::factory()->create();
        $this->exam = Exam::factory()->create(['center_id' => $this->center->id]);
        Carbon::setTestNow();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success_examiner(): void
    {
        $center = Center::factory()->create();
        $user = User::factory()->examiner()->create(['center_id' => $this->center->id]);
        $this->exam->examiners()->attach($user);
        $response = $this->actingAs($user)
            ->getJson(route('exams.show', ['exam' => $this->exam]));
        $response->assertStatus(200);
    }

    public function test_fail_no_attach_examiner(): void
    {
        $user = User::factory()->examiner()->create(['center_id' => $this->center->id]);
        $response = $this->actingAs($user)
            ->getJson(route('exams.show', ['exam' => $this->exam]));
        $response->assertStatus(403);
    }

    public function test_success_operator(): void{
        $user = User::factory()->operator()->create(['center_id' => $this->center->id]);
        $response = $this->actingAs($user)
            ->getJson(route('exams.show', ['exam' => $this->exam]));
        $response->assertStatus(200);
    }

    public function test_success_director(): void{
        $user = User::factory()->director()->create(['center_id' => $this->center->id]);
        $response = $this->actingAs($user)
            ->getJson(route('exams.show', ['exam' => $this->exam]));
        $response->assertStatus(200);
    }

    public function test_success_super_admin(): void{
        $user = User::factory()->superAdmin()->create(['center_id' => $this->center->id]);
        $response = $this->actingAs($user)
            ->getJson(route('exams.show', ['exam' => $this->exam]));
        $response->assertStatus(200);
    }
}
