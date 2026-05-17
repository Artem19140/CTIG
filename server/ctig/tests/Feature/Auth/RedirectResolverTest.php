<?php

namespace Tests\Feature\Auth;

use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectResolverTest extends TestCase
{
    use RefreshDatabase;

     protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);        
        Carbon::setTestNow(
            Carbon::now()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_operator(): void
    {
       $employee = Employee::factory()
        ->operator()
        ->create();
        $this->assertEquals(route('foreign-nationals.index'), $employee->resolveRedirect());
    }
    public function test_examiner(): void
    {
       $employee = Employee::factory()
        ->examiner()
        ->create();
        $this->assertEquals(route('exams.index'), $employee->resolveRedirect());
    }
    public function test_director(): void
    {
       $employee = Employee::factory()
        ->director()
        ->create();
        $this->assertEquals(route('foreign-nationals.index'), $employee->resolveRedirect());
    }
    public function test_org_admin(): void
    {
       $employee = Employee::factory()
        ->orgAdmin()
        ->create();
        $this->assertEquals(route('centers.show', ['center' => $employee->center]), $employee->resolveRedirect());
    }

    public function test_scheduler(): void
    {
       $employee = Employee::factory()
        ->scheduler()
        ->create();
        $this->assertEquals(route('exams.index'), $employee->resolveRedirect());
    }
}
