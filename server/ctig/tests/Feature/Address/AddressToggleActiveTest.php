<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressToggleActiveTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->orgAdmin()->create();
        Carbon::setTestNow(
            Carbon::now()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success(): void
    {
        $address = Address::factory()->create();
        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.toggle.active', ['address'=> $address]));

        $response->assertStatus(204);
    }
}
