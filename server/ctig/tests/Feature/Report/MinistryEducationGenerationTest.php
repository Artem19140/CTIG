<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MinistryEducationGenerationTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp():void{
        parent::setUp(); 
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->director()->create();
        
        Carbon::setTestNow(now());
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    public function test_success_last_week(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('reports.ministry-education.available', [
                'lastWeek' => true
            ]));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'redirectUrl'
            ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('reports.ministry-education',[
                'lastWeek' => true
            ]));

        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );
    }

    public function test_success_period(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('reports.ministry-education.available', [
                'lastWeek' => false,
                'dateFrom' => Carbon::now()->subWeek()->format('Y-m-d'),
                'dateTo' => Carbon::now()->subDays(4)->format('Y-m-d')
            ]));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'redirectUrl'
            ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('reports.ministry-education',[
                'lastWeek' => false,
                'dateFrom' => Carbon::now()->subWeek()->format('Y-m-d'),
                'dateTo' => Carbon::now()->subDays(4)->format('Y-m-d')
            ]));

        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );
    }

    public function test_fail_no_last_week(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('reports.ministry-education.available', [
            ]));
        $response->assertStatus(422);
    }

    public function test_fail_no_period_with_false_last_week(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('reports.ministry-education.available', [
                'lastWeek' => false,
                'dateFrom' => null,
                'dateTo' => null
            ]));
        $response->assertStatus(422);
    }
}
