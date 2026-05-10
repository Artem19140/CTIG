<?php

namespace Tests\Feature\ForeignNational;

use App\Enums\UserRoles;
use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;

class ForeignNationalExportTest extends TestCase
{

    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected string $dateFrom ;
    protected string $dateTo;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->director()->create();
        ForeignNational::factory(10)->create();
        Carbon::setTestNow();
        $this->dateFrom = Carbon::now()->subDay()->format('Y-m-d');
        $this->dateTo = Carbon::now()->addDay()->format('Y-m-d');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
    

        $response = $this->actingAs($this->user)
            ->getJson("foreign-nationals/export?dateFrom=$this->dateFrom&dateTo=$this->dateTo");

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

        $response = $this->actingAs($this->user)
            ->getJson("foreign-nationals/export/available");

        $response->assertUnprocessable();
    }

    public function test_access_roles(){
    
        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Director],
            method: 'GET',
            route: "foreign-nationals/export?dateFrom=$this->dateFrom&dateTo=$this->dateTo"
        );
    }
}
