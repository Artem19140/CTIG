<?php

namespace Tests\Feature\Attempt;

use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptLifecycleTest extends TestCase
{
    use RefreshDatabase;
    protected User  $user;
    protected Center $center;
    
    protected function setUp():void{
        parent::setUp();
        $this->center = Center::factory()->create();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->examiner()->create();
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }
}
