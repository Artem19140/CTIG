<?php

namespace Tests\Feature\Report;

use App\Models\Attempt;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlatTableGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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

    public function test_success(): void
    {
        Attempt::factory(5)->checked()->passed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $this->actingAs($this->user)
            ->get(route('reports.flat-table', [
                'dateFrom' => Carbon::now()->subDay()->format('Y-m-d'),
                'dateTo' => Carbon::now()->addDay()->format('Y-m-d')
            ]));

        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );

        $response->assertStatus(200);
    }
}
