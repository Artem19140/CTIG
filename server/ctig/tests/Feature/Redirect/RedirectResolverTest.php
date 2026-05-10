<?php

namespace Tests\Feature\Redirect;

use App\Http\RedirectResolver;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectResolverTest extends TestCase
{
    use RefreshDatabase;
    protected RedirectResolver $redirectResolver;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->redirectResolver = app(RedirectResolver::class);
        Carbon::setTestNow();
    }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success_operator(): void{
        $user = User::factory()->operator()->create();
        $response = $this->actingAs($user)
            ->getJson(route('me'));
        $response->assertRedirectToRoute('foreign-nationals.index'); 
    }
}
