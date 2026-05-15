<?php

namespace Tests\Feature\Report;

use App\Models\Attempt;
use App\Models\Center;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrdoGenerationAvailableTest extends TestCase
{
    use RefreshDatabase;
    protected Employee $actor;
    protected Center $center;
    protected function setUp():void{
        parent::setUp(); 
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->actor = Employee::factory()->director()->create(['center_id' => $this->center->id]);
        
        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    protected function getFrdo(bool $passed){
        return $this->actingAs($this->actor)
            ->getJson(route('reports.frdo.available', [
            'success' => $passed,
            'examDate' => Carbon::now()->format('Y-m-d')
        ]));
    }
    public function test_success_passed(): void
    {
        Attempt::factory(5)->checked()->passed()->create([
            'created_at' => Carbon::now(),
            'center_id' => $this->center->id
        ]);
        $response = $this->getFrdo(true);

        $response->assertOk();
    }

    public function test_success_not_passed(): void
    {
        Attempt::factory(5)->checked()->failed()->create([
            'created_at' => Carbon::now(),
        ]);

        Attempt::factory(5)->checked()->passed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $this->getFrdo(false);

        $response->assertOk();
    }

    public function test_fail_no_attempts(): void
    {
        $response = $response = $this->getFrdo(true);

        $response->assertBadRequest();
    }

    public function test_fail_not_checked_attempts(): void
    {
        Attempt::factory(5)->notChecked()->passed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $response = $this->getFrdo(true);

        $response->assertBadRequest();
    }

    public function test_fail_no_attempts_for_success(): void
    {
        Attempt::factory(5)->checked()->failed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $response = $this->getFrdo(true);

        $response->assertBadRequest();
    }

    public function test_fail_no_attempts_for_fail(): void
    {
        Attempt::factory(5)->checked()->passed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $response = $this->getFrdo(false);

        $response->assertBadRequest();
    }
}
