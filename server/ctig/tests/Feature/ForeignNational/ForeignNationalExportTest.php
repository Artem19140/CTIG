<?php

namespace Tests\Feature\ForeignNational;

use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForeignNationalExportTest extends TestCase
{

    use RefreshDatabase;
    protected User $user;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->director()->create();
        Carbon::setTestNow();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        
        ForeignNational::factory(10)->create();

        $dateFrom = Carbon::now()->subDay()->format('Y-m-d');
        $dateTo = Carbon::now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->getJson("foreign-nationals/export?dateFrom=$dateFrom&dateTo=$dateTo");

        $response->assertOk();

        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );

        $this->assertStringContainsString('attachment', 
            $response->headers->get('Content-Disposition')
        );
    }

    public function test_fail_no_date_range(): void
    {

        ForeignNational::factory(10)->create();
        $response = $this->actingAs($this->user)
            ->getJson("foreign-nationals/export/available");

        $response->assertUnprocessable();

        
    }
}
