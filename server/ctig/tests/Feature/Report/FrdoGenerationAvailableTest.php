<?php

namespace Tests\Feature\Report;

use App\Models\Attempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FrdoGenerationAvailableTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp():void{
        parent::setUp(); 
        $this->user = User::factory()->create();
        
        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    protected function getFrdo(bool $passed){
        return $this->actingAs($this->user)
            ->getJson(route('reports.frdo.available', [
            'success' => $passed,
            'examDate' => Carbon::now()->format('Y-m-d')
        ]));
    }
    public function test_success_passed(): void
    {
        Attempt::factory(5)->checked()->passed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $this->getFrdo(true);

        $response->assertOk();
    }

    public function test_success_not_passed(): void
    {
        Attempt::factory(5)->checked()->failed()->create([
            'created_at' => Carbon::now()
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
