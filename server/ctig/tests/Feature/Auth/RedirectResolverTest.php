<?php

namespace Tests\Feature\Auth;

use App\Models\User;
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
       $user = User::factory()
        ->operator()
        ->create();
        $this->assertEquals(route('foreign-nationals.index'), $user->resolveRedirect());
    }
    public function test_examiner(): void
    {
       $user = User::factory()
        ->examiner()
        ->create();
        $this->assertEquals(route('exams.monitoring'), $user->resolveRedirect());
    }
    public function test_director(): void
    {
       $user = User::factory()
        ->director()
        ->create();
        $this->assertEquals(route('foreign-nationals.index'), $user->resolveRedirect());
    }
    public function test_org_admin(): void
    {
       $user = User::factory()
        ->orgAdmin()
        ->create();
        $this->assertEquals(route('centers.show', ['center' => $user->center]), $user->resolveRedirect());
    }

    public function test_scheduler(): void
    {
       $user = User::factory()
        ->sheduler()
        ->create();
        $this->assertEquals(route('exams.index'), $user->resolveRedirect());
    }
}
